<?php

namespace App\Console\Commands;

use App\Models\ScheduledJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class RunAdminScheduledJobs extends Command
{
    protected $signature = 'admin:run-scheduled-jobs';
    protected $description = 'Run admin scheduled jobs';

    public function handle(): int
    {
        $now = now();

        $jobs = ScheduledJob::where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('next_run_at')
                    ->orWhere('next_run_at', '<=', $now);
            })
            ->get();

        foreach ($jobs as $job) {

            if (! in_array($job->command, config('admin-scheduler.allowed_commands'))) {
                continue;
            }

            $params = $job->parameters ?? [];

            /**
             * ✅ AUTO-INJECT DATE FOR DAILY FINANCIAL JOBS
             */
            if (
                in_array($job->command, [
                    'generate:roi',
                    'generate:level-withdraw',
                    'generate:dividend-withdraw',
                ]) &&
                ! isset($params['date']) &&
                ! isset($params['income_date'])
            ) {
                $params['date'] = now()->format('Y-m-d');
            }

            if ($job->skip_holidays) {
                $isHoliday = DB::table('holidays')
                    ->where('holiday_date', now()->toDateString())
                    ->exists();

                if ($isHoliday) {
                    // Skip ONLY this job, continue others
                    continue;
                }
            }

            try {
                Artisan::call($job->command, $params);

                $job->update([
                    'last_run_at' => $now,
                    'next_run_at' => $job->calculateNextRun(),
                    'last_error' => null,
                ]);

                DB::table('scheduled_job_logs')->insert([
                    'scheduled_job_id' => $job->id,
                    'status' => 'success',
                    'message' => 'Executed successfully',
                    'ran_at' => $now,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            } catch (\Throwable $e) {

                $job->update([
                    'last_failed_at' => $now,
                    'last_error' => $e->getMessage(),
                ]);

                DB::table('scheduled_job_logs')->insert([
                    'scheduled_job_id' => $job->id,
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                    'ran_at' => $now,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

        }

        return SymfonyCommand::SUCCESS;
    }

}
