<?php

namespace App\Listeners;

use App\Events\Registered;
use App\Methods\UserLevelMethods;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateTeamUserLevels implements ShouldQueue
{
    use InteractsWithQueue;


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
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        UserLevelMethods::init($event->user)->updateUplineStats(15);
    }
}
