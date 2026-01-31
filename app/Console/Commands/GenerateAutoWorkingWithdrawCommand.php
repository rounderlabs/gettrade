<?php

namespace App\Console\Commands;

use App\Jobs\GenerateAutoWorkingWithdrawHistoryJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class GenerateAutoWorkingWithdrawCommand extends Command
{
    protected $signature = 'generate:working-withdraw {date}';
    protected $description = 'Generate auto working withdrawal for a given date';

    public function handle(): int
    {
        $date = $this->argument('date');

        $validator = Validator::make(
            ['date' => $date],
            ['date' => ['required', 'date']]
        );

        if ($validator->fails()) {
            $this->error('❌ Invalid date. Format: YYYY-MM-DD');
            return SymfonyCommand::FAILURE;
        }

        /**
         * ✅ IDEMPOTENCY CHECK
         */
        $alreadyDone = DB::table('working_withdrawal_history_closings')
            ->where('closing_date', $date)
            ->where('status', 'success')
            ->exists();

        if ($alreadyDone) {
            $this->warn("⚠ Working withdrawal already generated for {$date}");
            return SymfonyCommand::SUCCESS;
        }

        GenerateAutoWorkingWithdrawHistoryJob::dispatch($date)
            ->delay(now()->addSecond());

        $this->info("✅ Working withdrawal job dispatched for {$date}");

        return SymfonyCommand::SUCCESS;
    }
}
