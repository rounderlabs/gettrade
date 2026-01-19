<?php

namespace App\Listeners;

use App\Events\SubscriptionCreated;

class CreateUserRank
{


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param SubscriptionCreated $event
     * @return void
     */
    public function handle(SubscriptionCreated $event)
    {
        $user = $event->user;
    }
}
