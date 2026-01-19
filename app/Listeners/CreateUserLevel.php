<?php

namespace App\Listeners;

use App\Events\Registered;

class CreateUserLevel
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
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $event->user->userLevels()->create([
            'level' => 1,
            'team' => 0,
            'active_team' => 0
        ]);
    }
}
