<?php

namespace GistApi\Listeners;

use GistApi\Events\PackageSubmitted;
use GistApi\Repositories\Package;

class PushSlackNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PodcastWasPurchased  $event
     * @return void
     */
    public function handle(PackageSubmitted $event)
    {
        $pacakge = Package::find($event->package);
        \Log::info(" New Package Submitted \n ".$package->name);
        \Slack::send(" New Package Submitted \n ".$package->name);
    }
}
