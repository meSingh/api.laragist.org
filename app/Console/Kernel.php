<?php

namespace GistApi\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use GistApi\Repositories\Package;
use GistApi\Repositories\PackageRepo;
use GistApi\Repositories\PackageVersion;
use GistApi\Repositories\Author;
use GistApi\Repositories\Category;
use URL;
use GuzzleHttp\Client;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //          
            
        $schedule->call(function ()
        {
            $client = new Client();
            $packages = Package::where('status', 1)->get();

            foreach ($packages as $package) 
            {
                \Log::info('Fetching data for ' . $package->name);
                try{
                    // Make the request to get package content
                    $response = $client->request('GET', "https://packagist.org/packages/" . $package->name . ".json");
                }
                catch(\GuzzleHttp\Exception\ClientException $e)
                {
                    continue;
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
                $package->update([
                    'description'   =>  $data->description,
                    'maintainers'   =>  json_encode($data->maintainers),
                    'support'       =>  json_encode([]),
                    'type'          =>  $data->type,
                    'repository'    =>  $data->repository,
                    'downloads_total'   =>  $data->downloads->total,
                    'downloads_monthly' =>  $data->downloads->monthly,
                    'downloads_daily'   =>  $data->downloads->daily,
                    'favorites'         =>  $data->favers,
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
                $package->authors()->sync($authors);

                foreach ($versions as $versionData) 
                {
                    $insert = PackageVersion::firstOrNew([
                        'package_id' => $package->id,
                        'version'   => $versionData->version,
                    ]);

                    $insert->data = collect($versionData)->toJson();
                    $insert->save();
                }

                // Update various other data
                $package->keywords = implode(',', $latest->keywords);
                $package->license = implode(',', $latest->license);
                $package->version = $version;
                $package->homepage = empty( $latest->homepage ) ? $data->repository : $latest->homepage;
                $package->last_updated = $latest->time;
                $package->save();
                
            }


        // })->when(function () {
        //     return true;
        // });
        })->hourly();





        $schedule->call(function () 
        {

            // create new sitemap object
            $sitemap = \App::make("sitemap");

            // add items to the sitemap (url, date, priority, freq)
            $sitemap->add(  URL::to('/'),         \Carbon\Carbon::now(), '1.0', 'daily');
            $sitemap->add(  URL::to('submit'),    \Carbon\Carbon::now(), '0.8', 'daily');
            $sitemap->add(  URL::to('about'),     \Carbon\Carbon::now(), '0.5', 'weekly');
            $sitemap->add(  URL::to('support'),   \Carbon\Carbon::now(), '0.5', 'weekly');
            // get all posts from db
            $packages = \DB::table('packages')->orderBy('created_at', 'desc')->get();

            // add every post to the sitemap
            foreach ($packages as $package)
            {
                $sitemap->add(URL::to('package/' . $package->name), $package->updated_at, '0.9', 'daily');
            }

            // generate your sitemap (format, filename)
            $sitemap->store('xml', 'sitemap');
            // this will generate file sitemap.xml to your public folder

//         })->when(function () {
//             return true;
//         });
        })->daily();
    }
}
