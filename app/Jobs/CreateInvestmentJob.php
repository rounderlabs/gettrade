<?php

namespace App\Jobs;

use App\Models\Plan;
use App\Models\User;
use App\Models\UserUsdWalletTransaction;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class CreateInvestmentJob implements ShouldQueue
{
    use  Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;
    public Plan $plan;
    public User $user;
    public UserUsdWalletTransaction $userUsdWalletTransaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct(Plan $plan, User $user, UserUsdWalletTransaction $userUsdWalletTransaction)
    {
        $this->user = $user->withoutRelations();
        $this->plan = $plan;
        $this->userUsdWalletTransaction = $userUsdWalletTransaction->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $plan = $this->plan;
        if (is_null($plan)) {
            return;
        }
        $is_active = true;
        $totalBusiness = castDecimalString($this->userUsdWalletTransaction->amount_in_usd, 2);

        $subscription = $this->user->subscriptions()->create([
                'plan_id' => $plan->id,
                'user_usd_wallet_transaction_id' => $this->userUsdWalletTransaction->id,
                'end_date' => $this->userUsdWalletTransaction->created_at->addMonths(12)->format('Y-m-d'),
                'amount' => $this->userUsdWalletTransaction->amount_in_usd,
                'earned_so_far' => castDecimalString('0', 2),
                'is_active' => $is_active,
                'max_income_limit' => multipleDecimalStrings($this->userUsdWalletTransaction->amount_in_usd, $plan->max_earning_percent_decimal, 4),
                'created_at' => $this->userUsdWalletTransaction->created_at
            ]);


        $amount = $this->userUsdWalletTransaction->amount_in_usd;
        $walletType = 'USDT Wallet';
        $currency = 'USDT';
        $txn_type = 'Debit';
        $remark = 'Plan Subscription of $'.$amount.' has been Debited';
        CreateUserWalletLedgerJob::dispatch($this->user, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());


        if ($subscription) {
            dispatch(new UpdateActiveTeamStatJob($this->user))->delay(now()->addSeconds(5));
        }

        $userActiveModel = $this->user->userActive;
        if (is_null($userActiveModel)) {
            $this->user->userActive()->create([
                'active_at' => now()
            ]);
        }
        $batch = Bus::batch([
            new CreateUserBusinessJob($this->user, $totalBusiness, $this->userUsdWalletTransaction),

        ])->then(function (Batch $batch) {
        })->finally(function (Batch $batch) {
        })->onQueue('default')->dispatch();
    }

}
