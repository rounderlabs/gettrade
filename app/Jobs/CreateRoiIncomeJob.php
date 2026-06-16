<?php

namespace App\Jobs;

use App\Models\Plan;
use App\Models\RoiIncomeClosing;
use App\Models\Subscription;
use App\Services\Income\IncomeService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateRoiIncomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Subscription $subscription;
    private RoiIncomeClosing $closing;

    public function __construct(Subscription $subscription, RoiIncomeClosing $closing)
    {
        $this->onQueue('income');
        $this->subscription = $subscription->withoutRelations();
        $this->closing = $closing->withoutRelations();
    }

    public function handle(): void
    {
        $this->subscription->refresh();
        $user = $this->subscription->user;
        $userStop = userStop($user);

        if ($userStop->is_blocked || $userStop->roi) {
            return;
        }
        if (! $user || ! isUserActive($user)) return;
        if (! $this->subscription->is_active) return;

        if (
            $user->userRoiIncomes()
                ->where('subscription_id', $this->subscription->id)
                ->where('closing_date', $this->closing->closing_date)
                ->exists()
        ) {
            return;
        }

        $plan = Plan::find($this->subscription->plan_id);
        if (! $plan) return;

        /* ===============================
           MONTHLY % → DAILY ROI AMOUNT
        =============================== */
        $monthlyPercent = castDecimalString($plan->monthly_roi_amount, 4);

        // Check if booster is active for this subscription
        $isBoosterActive = \App\Models\UserBoosterStat::where('subscription_id', $this->subscription->id)
            ->where('is_booster_active', 1)
            ->exists();

        if ($isBoosterActive) {
            $monthlyPercent = addDecimalStrings($monthlyPercent, '1.00', 4);
        }

        $monthlyAmount  = multipleDecimalStrings(
            $this->subscription->amount,
            divDecimalStrings($monthlyPercent, '100', 6),
            4
        );

        $days = Carbon::parse($this->closing->closing_date)->daysInMonth;
        $dailyAmount = divDecimalStrings($monthlyAmount, $days, 2);

        if ($dailyAmount <= 0) return;

        /* ===============================
           CREDIT BASE PASSIVE INCOME
        =============================== */
        $credited = IncomeService::creditPassiveIncome(
            $this->subscription,
            $dailyAmount
        );

        if ($credited <= 0) return;

        /* ===============================
           SAVE ROI ENTRY
        =============================== */
        $roiIncome = $user->userRoiIncomes()->create([
            'subscription_id' => $this->subscription->id,
            'closing_date'    => $this->closing->closing_date,
            'amount'          => $this->subscription->amount,
            'income'          => $credited,
        ]);

        userIncomeStat($user)->increment('roi', $credited);
        userIncomeStat($user)->increment('total', $credited);

        userIncomeOnHold($user)->increment('roi', $credited);
        userIncomeOnHold($user)->increment('total', $credited);

        CreateUserWalletLedgerJob::dispatch(
            $user,
            'Income Wallet',
            'INR',
            'Credit',
            $credited,
            "Daily Trading Bonus credited"
        )->delay(now()->addSecond());

        /* ===============================
           NEXT STEPS
        =============================== */
        dispatch(new CreateRankRoiIncomeJob($roiIncome))->afterCommit();
        dispatch(new GenerateLevelRoiIncomeJob($roiIncome))->afterCommit();
    }
}
