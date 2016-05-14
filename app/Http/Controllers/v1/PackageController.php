<?php

namespace GistApi\Http\Controllers\v1;

/*
 *
 * Statuses:
 * 0 inactive
 * 1 active
 * 2 pending approval
 * 3 rejected
 */
use GistApi\Repositories\User;
use GistApi\Mailers\UserMailer;

use Illuminate\Http\Request;
use GistApi\Http\Requests\v1\Package\StoreRequest;
use GuzzleHttp\Client;
use GistApi\Repositories\Author;
use GistApi\Repositories\Package;
use GistApi\Repositories\PackageRepo;
use GistApi\Transformers\PackagesTransformer;
use GistApi\Transformers\PackageTransformer;

use GistApi\Http\Controllers\v1\ApiController;

use GistApi\Events\PackageSubmitted;

class PackageController extends ApiController
{
    public function index(Request $request)
    {
        $packages = Package::select([
                            'packages.id as id',
                            'packages.name as name',
                            'packages.description as description',
                            'packages.downloads_total as downloads_total',
                            'packages.favorites as favorites',
                            'packages.version as version', 
                            'packages.last_updated as last_updated'
                        ])
                        ->with('categories')
                        ->where('packages.status', 1);


        if( $request->has('q') && !empty($request->get('q')) )
            $packages = $packages->where('packages.name','LIKE', 
                                            '%' . $request->get('q') . '%');

        if( $request->has('sortby') && !empty($request->get('sortby')) )
        {
            switch ($request->get('sortby')) 
            {
                case 'mp':
                    $packages = $packages->orderBy('packages.favorites', 'DESC');
                    break;

                case 'md':
                    $packages = $packages->orderBy('packages.downloads_total', 'DESC');
                    break;

                case 'ru':
                    $packages = $packages->orderBy('packages.last_updated', 'DESC');
                    break;
            }
        }

        if( $request->has('cid') && !empty($request->get('cid')) )
            $packages = $packages
                            ->join('package_categories', 
                                    'package_categories.package_id', '=', 'packages.id')
                            ->whereIn('package_categories.category_id',json_decode($request->get('cid')));
            
                            
        $packages = $packages->paginate(20);             

        return $this->response->paginator($packages, new PackagesTransformer);

    }
 
    /**
     * Store Package data
     *
     * @param \Illuminate\Http\Request $request
     * @return null or error
     */
    protected function store(StoreRequest $request)
    {
         // $client = new Client(['debug'=>true]);
        $client = new Client();


        //  Create the user if not exists
        $user = User::firstOrNew([ 'email' => $request->email ]);
        $user->first_name = $request->first_name;
        $user->save();


        // Check if package already exists in db
        $check = Package::whereName( $request->name )->first();
        if(!is_null($check))
        {
            switch($check->status)
            {
                case 0:
                    return $this->response->errorBadRequest('Package already exist in laragist & is set to inactive!');
                    break;
                case 1:
                    return $this->response->errorBadRequest('Package already exist in laragist!');
                    break;
                case 2:
                    return $this->response->errorBadRequest('Package already exist in laragist & is awaiting approval!');
                    break;
                case 3:
                    return $this->response->errorBadRequest('Package already exist in laragist & was rejected!');
                    break;
            }
        }

        try{
            // Make the request to get package content
            $response = $client->request('GET', "https://packagist.org/packages/" . $request->name . ".json");

        }
        catch(\GuzzleHttp\Exception\ClientException $e)
        {
            return $this->response->errorBadRequest('Package not found!');
        }

        $data = json_decode( $response->getBody()->getContents() )->package;

        // Get versions data
        $versions = collect($data->versions)->sortByDesc('version_normalized');

        // Calculate latest version and its data
        $versionIds = $versions->keys();
        $versionIds = $versionIds->reject(function ($versionId, $key) {
            $return = (strpos($versionId, 'dev') !== false);

            if(!$return)
                $return = (strpos($versionId, 'master') !== false);

            return $return;
        });
        if( $versionIds->count() )
        {
            $version = $versionIds->first();
            $latest = $versions->all()[$version];
        }
        else
        {
            $latest = $versions->first();   
            $version = $latest->version;
        }

        // Add data to db
        $package = Package::create([
            'name'  =>  $data->name,
            'description'  =>  $data->description,
            'maintainers'  =>  json_encode($data->maintainers),
            'support'  =>  json_encode([]),
            'type'  =>  $data->type,
            'repository'  =>  $data->repository,
            'downloads_total'  =>  $data->downloads->total,
            'downloads_monthly'  =>  $data->downloads->monthly,
            'downloads_daily'  =>  $data->downloads->daily,
            'favorites'  =>  $data->favers,
            'created'  =>  $data->time,
            'user_id'  =>  $user->id,
        ]);

        // Find authors and insert data
        $authors = [];
        foreach ($latest->authors as $author) 
        {
            if( isset($author->email) )
            {
                $authorRepo = Author::firstOrNew([
                    'email' =>  $author->email
                ]);
            }
            else
            {
                $authorRepo = new Author;
            }
            
            $authorRepo->name = $author->name;
            $authorRepo->homepage = isset( $author->homepage ) ? $author->homepage : "";
            $authorRepo->role = isset( $author->role ) ? $author->role : "";

            $authorRepo->save();


            $authors[] = $authorRepo->id;
        }
        $package->authors()->attach($authors);
        
        // Attach categories
        $package->categories()->attach( $request->category_id );

        // Insert versions data to mongo
        $repo = PackageRepo::create( [ 
                'versions' => $versions->values()->map(
                function ($package) {
                    $pacakge->extra->branch-alias = [];
                    return $package;
                })->all()] );

        // Update various other data
        $package->keywords = implode(',', $latest->keywords);
        $package->license = implode(',', $latest->license);
        $package->version = $version;
        $package->homepage = empty( $latest->homepage ) ? $data->repository : $latest->homepage;
        $package->last_updated = $latest->time;
        $package->object_id = $repo->_id;
        $package->save();

        \Slack::send("New Package Submitted: \n ".$package->name . "\n by " . $user->first_name);


        return $this->response->noContent();
    }

    public function show( $user, $name)
    {
        $package = Package::whereName($user . '/' . $name)->with('categories')->first();

        if(is_null($package)) return $this->response->errorBadRequest('Package does not exists!');

        $packageRepo = PackageRepo::find($package->object_id);
        if(!is_null($packageRepo))
        {
            $package->versions = $packageRepo->versions;
            $package->latest = collect($packageRepo->versions)
                                    ->where('version', $package->version)
                                    ->first();
        }
        
        
        return $this->response->item($package, new PackageTransformer);
    }

    public function underReview()
    {
        $packages = Package::select([
                            'packages.id as id',
                            'packages.name as name',
                            'packages.description as description',
                            'packages.downloads_total as downloads_total',
                            'packages.favorites as favorites',
                            'users.first_name as user',
                            // \DB::raw('CONCAT(users.first_name, " ", if(users.last_name is null ,"", users.last_name)) as user'),
                        ])
                        ->join('users', 'users.id', '=', 'packages.user_id')
                        ->where('packages.status', 0)
                        ->paginate(20);             

        return $this->response->paginator($packages, new PackagesTransformer);
    }

}
