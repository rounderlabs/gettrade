<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\ShortSchedule\ShortSchedule;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        /**
         * ✅ Admin-controlled scheduler
         * This is the ONLY scheduled entry
         */
        $schedule->command('admin:run-scheduled-jobs')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->command('crypto:fetch-prices')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->onOneServer();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }

    /**
     * Short schedule (sub-minute tasks)
     */
    protected function shortSchedule(ShortSchedule $shortSchedule): void
    {
        $shortSchedule
            ->command('redis:heart-beat')
            ->everySeconds(10)
            ->withoutOverlapping();
    }
}
