<?php

namespace App\Jobs;

use App\Models\RoiIncomeClosing;
use App\Models\Subscription;
use App\Models\UserInvestment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateRoiIncomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $incomeDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $incomeDate)
    {
        $this->onQueue('income');
        $this->incomeDate = $incomeDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $today = $this->incomeDate;
        $closing = RoiIncomeClosing::firstOrCreate(
            ['closing_date' => $today],
            ['status' => 'pending']
        );
        if ($closing->status === 'pending') {
            $subscriptions = Subscription::where('is_active','=',  1)->get();
            foreach ($subscriptions as $subscription) {
                CreateRoiIncomeJob::dispatch($subscription, $closing)->delay(now()->addSecond());
            }
            $closing->update([
                'status' => 'success'
            ]);
        }
    }
}
