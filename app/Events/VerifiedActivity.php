<?php

namespace App\Events;

use App\Webhook\Providers\Alchemy\AddressActivity;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerifiedActivity
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public AddressActivity $activity;

    /**
     * Create a new event instance.
     *
     * @param AddressActivity $activity
     */
    public function __construct(AddressActivity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
