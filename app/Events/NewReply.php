<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewReply extends Event
{
    use SerializesModels;

    public $reply;

    /**
     * Create a new event instance.
     *
     * @param \App\Reply $reply
     * @return void
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }
}
