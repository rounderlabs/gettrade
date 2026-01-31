<?php

namespace App\Jobs;

use App\Models\WorkingWithdrawalHistoryClosing;
use App\Models\UserIncomeOnHold;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAutoWorkingWithdrawHistoryJob implements ShouldQueue
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

        $closing = WorkingWithdrawalHistoryClosing::firstOrCreate(
            ['closing_date' => $levelWithdrawDate],
            ['status' => 'pending']
        );
        if ($closing->status === 'pending') {
            $userIncomeOnHolds = UserIncomeOnHold::where('direct', '>', 0)->get();
            foreach ($userIncomeOnHolds as $userIncomeOnHold) {
                CreateAutoWorkingWithdrawHistoryJob::dispatch($userIncomeOnHold)->delay(now()->addSecond());
            }
            $closing->update([
                'status' => 'success'
            ]);
        }

    }
}
