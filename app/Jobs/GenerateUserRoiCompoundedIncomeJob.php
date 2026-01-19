<?php

namespace App\Jobs;

use App\Models\UserRoiCompoundedClosing;
use App\Models\UserRoiCompounding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateUserRoiCompoundedIncomeJob implements ShouldQueue
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
        $closing = UserRoiCompoundedClosing::firstOrCreate(
            ['closing_date' => $today],
            ['status' => 'pending']
        );
        if ($closing->status == 'pending') {
            $compoundings = UserRoiCompounding::where('start_date', '<', $today)->where('is_compounded', 1)->get();
            foreach ($compoundings as $compounded) {
                CreateUserRoiCompoundedIncomeJob::dispatch($compounded, $closing)->delay(now()->addSeconds(1));
            }
        }
        $closing->update([
            'status' => 'success'
        ]);
    }
}
