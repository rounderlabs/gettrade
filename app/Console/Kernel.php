<?php

namespace App\Console;

use App\Jobs\GenerateRoiIncomeJob;
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
        // ✅ Regular Laravel scheduler (every minute check)
        $schedule->command('redis:heart-beat')
            ->everyFiveSeconds()
            ->withoutOverlapping();

        // ✅ Update user ranks once per day at 12:10 AM
        // $schedule->command('update:rank')
        //    ->dailyAt('00:10')
         //   ->withoutOverlapping();

        // ✅ Generate ROI incomes daily at 12:01 AM
//        $schedule->job(new GenerateRoiIncomeJob(now()->format('Y-m-d')))
//            ->dailyAt('00:01')
//            ->withoutOverlapping();

        $schedule->job(new GenerateRoiIncomeJob(now()->format('Y-m-d')))
            ->dailyAt('00:01')
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
     * Short schedule for sub-minute task intervals (using spatie/laravel-short-schedule)
     */
    protected function shortSchedule(ShortSchedule $shortSchedule): void
    {
        $shortSchedule->command('redis:heart-beat')->everySeconds(10);
    }
}
