<?php

namespace App\Jobs;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserUsdWallet;
use App\Models\UserUsdWalletTransaction;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class CreateAutoRenewSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $timeout = 600;
    public User $user;
    public Subscription $subscription;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription, User $user)
    {
        $this->user = $user->withoutRelations();
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->subscription->is_active == 1){
            return;
        }
        if ($this->subscription->earned_so_far < $this->subscription->max_income_limit){
            return;
        }
        $amount = multipleDecimalStrings(castDecimalString($this->subscription->amount, 2),2);
        $userUsdWallet = UserUsdWallet::where('user_id', $this->user->id)->lockForUpdate()->first();
        if (is_null($userUsdWallet)) {
            DB::rollBack();

        }
        if ($userUsdWallet->balance < $amount) {
            DB::rollBack();
        }
        $summary = UserUsdWalletTransaction::TXN_SUMMARY['AUTO_ACTIVATION_PLAN'];
        $walletTransaction = UserUsdWalletTransaction::create([
            'user_id' => $this->user->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd' => $amount,
            'last_amount' => $userUsdWallet->balance,
            'summary' => $summary,
            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time' => now()
        ]);
        $userUsdWallet->decrement('balance', $amount);

        if (is_null($walletTransaction)) {
            DB::rollBack();
        }
        DB::commit();
        $plan = Plan::where('id', $this->subscription->plan_id)->first();

        CreateSubscriptionJob::dispatch($plan, $request->user(), $walletTransaction)->delay(now());
    }
}
