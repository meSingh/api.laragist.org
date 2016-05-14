<?php

namespace GistApi\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use GistApi\Repositories\Package;
use GistApi\Repositories\PackageRepo;
use GistApi\Repositories\Author;
use GistApi\Repositories\Category;

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
            
        $schedule->call(function () {
            $client = new Client();
            $packages = Package::where('status', 1)->get();

            foreach ($packages as $package) 
            {
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
                $package->authors()->attach($authors);

                // Insert versions data to mongo
                $packageRepo = PackageRepo::find($package->object_id)
                                ->update([ 
                                    'versions' => $versions->values()->map(
                                        function ($package) {
                                            $pacakge['extra']['branch-alias'] = [];
                                            return $package;
                                        })->all()]);

                // Update various other data
                $package->keywords = implode(',', $latest->keywords);
                $package->license = implode(',', $latest->license);
                $package->version = $version;
                $package->homepage = empty( $latest->homepage ) ? $data->repository : $latest->homepage;
                $package->last_updated = $latest->time;
                $package->save();
                
            }


        })->when(function () {
            return true;
        });
        // })->hourly();
    }
}
