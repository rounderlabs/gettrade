<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateDailyIncomeStatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public User $user;
    public string $income_amount;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $income_amount)
    {
        $this->user = $user;
        $this->income_amount = $income_amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userDailyIncomeStat = userDailyIncomeStat($this->user, now()->format("Y-m-d"));
        $userDailyIncomeStat->increment('income_amount', $this->income_amount);
    }
}
