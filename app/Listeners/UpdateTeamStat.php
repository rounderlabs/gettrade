<?php

namespace App\Listeners;

use App\Events\Registered;
use App\Methods\TeamStat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateTeamStat implements ShouldQueue
{
    use InteractsWithQueue;

    public $timeout = 600;

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
        TeamStat::init($event->user)->updateStatToUpline();
    }
}
