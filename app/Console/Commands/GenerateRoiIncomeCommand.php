<?php

namespace App\Console\Commands;

use App\Jobs\GenerateRoiIncomeJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class GenerateRoiIncomeCommand extends Command
{
    /**
     * Command signature
     */
    protected $signature = 'generate:roi {date}';

    /**
     * Description
     */
    protected $description = 'Generate ROI income for a given date';

    /**
     * Execute the command
     */
    public function handle(): int
    {
        $incomeDate = $this->argument('date');

        $validator = Validator::make(
            ['income_date' => $incomeDate],
            ['income_date' => ['required', 'date']]
        );

        if ($validator->fails()) {
            $this->error('❌ Invalid income_date. Format: YYYY-MM-DD');
            return SymfonyCommand::FAILURE;
        }

        /**
         * ✅ IDEMPOTENCY CHECK (CRITICAL)
         * Prevent double ROI generation
         */
        $alreadyDone = DB::table('roi_income_closings')
            ->where('closing_date', $incomeDate)
            ->where('status', 'success')
            ->exists();

        if ($alreadyDone) {
            $this->warn("⚠ ROI already generated for {$incomeDate}");
            return SymfonyCommand::SUCCESS;
        }

        /**
         * Dispatch job
         */
        GenerateRoiIncomeJob::dispatch($incomeDate)
            ->delay(now()->addSecond());

        $this->info("✅ ROI job dispatched for {$incomeDate}");

        return SymfonyCommand::SUCCESS;
    }
}
