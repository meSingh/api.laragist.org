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

use GuzzleHttp\Client;
use GistApi\Repositories\Author;
use GistApi\Repositories\Package;
use GistApi\Repositories\PackageRepo;

use GistApi\Http\Controllers\v1\ApiController;

class PackageController extends ApiController
{
 
    /**
     * Store Package data
     *
     * @param \Illuminate\Http\Request $request
     * @return null or error
     */
    protected function store(Request $request)
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
        $version = $versionIds->first();
        $latest = $versions->all()[$version];

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
            $authorRepo = Author::firstOrNew([
                'email' =>  $author->email
            ]);

            $authorRepo->name = $author->name;
            $authorRepo->homepage = isset( $author->homepage ) ? $author->homepage : "";
            $authorRepo->role = isset( $author->role ) ? $author->role : "";

            $authorRepo->save();


            $authors[] = $authorRepo->id;
        }
        $package->authors()->attach($authors);

        // Insert versions data to mongo
        $repo = PackageRepo::create( [ 'versions' => $versions->values()->all()] );

        // Update various other data
        $package->keywords = implode(',', $latest->keywords);
        $package->license = implode(',', $latest->license);
        $package->version = $version;
        $package->homepage = $latest->homepage;
        $package->last_updated = $latest->time;
        $package->object_id = $repo->_id;
        $package->save();

        return $this->response->noContent();
    }

    public function search(Request $request)
    {
        $package =  PackageRepo::find('57127c3f9a892009c3200d24');
        return $package->versions;
    }

}
