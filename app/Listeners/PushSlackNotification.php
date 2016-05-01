<?php

namespace GistApi\Listeners;

use GistApi\Events\PackageSubmitted;

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
        \Slack::send(" New Package Submitted \n ".$event->package->name);
    }
}