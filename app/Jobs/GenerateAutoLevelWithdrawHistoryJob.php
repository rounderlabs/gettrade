<?php

namespace App\Jobs;

use App\Models\LevelWithdrawalHistoryClosing;
use App\Models\UserIncomeOnHold;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAutoLevelWithdrawHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $levelWithdrawDate;
    /**
     * Create a new job instance.
     */
    public function __construct(string $levelWithdrawDate)
    {
        $this->onQueue('income');
        $this->levelWithdrawDate = $levelWithdrawDate;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $levelWithdrawDate = $this->levelWithdrawDate;

        $closing = LevelWithdrawalHistoryClosing::firstOrCreate(
            ['closing_date' => $levelWithdrawDate],
            ['status' => 'pending']
        );
        if ($closing->status === 'pending') {
            $userIncomeOnHolds = UserIncomeOnHold::where('level', '>', 0)->get();
            foreach ($userIncomeOnHolds as $userIncomeOnHold) {
                CreateAutoLevelWithdrawHistoryJob::dispatch($userIncomeOnHold)->delay(now()->addSecond());
            }
            $closing->update([
                'status' => 'success'
            ]);
        }

    }
}
