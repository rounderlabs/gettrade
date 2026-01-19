<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Registered' => [
            'App\Listeners\UpdateTeamStat',
            'App\Listeners\UpdateTeamUserLevels',
            'App\Listeners\SendEmailVerificationNotification'
        ],
        'App\Events\VerifiedActivity' => [
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
