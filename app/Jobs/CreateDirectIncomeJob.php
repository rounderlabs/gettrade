<?php

namespace App\Jobs;

use App\Models\SiteSetting;
use App\Models\Subscription;
use App\Models\UserUsdWalletTransaction;
use App\Services\Income\IncomeService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateDirectIncomeJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public UserUsdWalletTransaction $transaction;
    public Subscription $subscription;

    public function __construct(
        UserUsdWalletTransaction $transaction,
        Subscription             $subscription
    )
    {
        $this->transaction = $transaction;
        $this->subscription = $subscription;
    }

    public function handle()
    {
        $user = $this->transaction->user;
        $parent = $user->sponsor;

        // ❌ No sponsor or inactive sponsor
        if (!$parent || !isUserActive($parent)) {
            return;
        }

        // ❌ Sponsor has no active subscription
        $parentSubscription = $parent->subscriptions()
            ->where('is_active', true)
            ->orderBy('id')
            ->first();

        if (!$parentSubscription) {
            return;
        }

        $userStop = userStop($parent);
        if ($userStop->is_blocked || $userStop->direct) {
            return;
        }


        /* ===============================
           CALCULATE DIRECT BONUS
        =============================== */
        $directPercent = SiteSetting::get('direct_percent', 0);
        if ($directPercent <= 0) {
            return;
        }

        $percentDecimal = divDecimalStrings($directPercent, 100, 4);
        $rawIncome = multipleDecimalStrings(
            $this->transaction->amount_in_usd,
            $percentDecimal,
            4
        );

        /* ===============================
           CREDIT WORKING INCOME (3× CAP)
        =============================== */
        $creditedIncome = IncomeService::creditWorkingIncome(
            $parentSubscription,
            $rawIncome
        );

        if ($creditedIncome <= 0) {
            return;
        }

        /* ===============================
           RECORD INCOME
        =============================== */
        $parent->userDirectIncomes()->create([
            'from_user'=>$this->subscription->user_id,
            'subscription_id' => $this->subscription->id,
            'income' => $creditedIncome,
        ]);

        userIncomeStat($parent)->increment('direct', $creditedIncome);
        userIncomeStat($parent)->increment('total', $creditedIncome);

        userIncomeOnHold($parent)->increment('direct', $creditedIncome);
        userIncomeOnHold($parent)->increment('total', $creditedIncome);

        /* ===============================
           LEDGER ENTRY
        =============================== */
        CreateUserWalletLedgerJob::dispatch(
            $parent,
            'Income Wallet',
            'INR',
            'Credit',
            $creditedIncome,
            "Direct Bonus of ₹{$creditedIncome} credited"
        )->delay(now()->addSecond());
    }
}
