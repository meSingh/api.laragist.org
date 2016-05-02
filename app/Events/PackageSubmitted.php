<?php

namespace GistApi\Events;

use GistApi\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use GistApi\Repositories\Package;

class PackageSubmitted extends Event
{
    use SerializesModels;

    public $package;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $package)
    {
        $this->package = $package;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
