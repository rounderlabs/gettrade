<?php

namespace App\Console\Commands;

use App\Jobs\GenerateAutoNonWorkingWithdrawHistoryJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class GenerateAutoNonWorkingWithdrawCommand extends Command
{
    protected $signature = 'generate:non-working-withdraw {date}';
    protected $description = 'Generate Auto Non Working Income withdrawal For a Given Date';

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
        $alreadyDone = DB::table('non_working_withdrawal_history_closings')
            ->where('closing_date', $date)
            ->where('status', 'success')
            ->exists();

        if ($alreadyDone) {
            $this->warn("⚠ Non Working Income withdrawal already generated for {$date}");
            return SymfonyCommand::SUCCESS;
        }

        GenerateAutoNonWorkingWithdrawHistoryJob::dispatch($date)
            ->delay(now()->addSecond());

        $this->info("✅Non Working Income withdrawal job dispatched for {$date}");

        return SymfonyCommand::SUCCESS;
    }
}
