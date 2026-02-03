<?php

namespace App\Jobs;

use App\Models\UserRank;
use App\Models\UserRankRoiIncomes;
use App\Models\UserRoiIncome;
use App\Services\Income\IncomeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateRankRoiIncomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private UserRoiIncome $roiIncome;

    public function __construct(UserRoiIncome $roiIncome)
    {
        $this->onQueue('income');
        $this->roiIncome = $roiIncome->withoutRelations();
    }

    public function handle(): void
    {
        $this->roiIncome->refresh();

        $user = $this->roiIncome->user;
        $subscription = $this->roiIncome->subscription;

        /* ===============================
           BASIC SAFETY
        =============================== */
        if (! $user || ! isUserActive($user)) return;
        if (! $subscription || ! $subscription->is_active) return;

        /* ===============================
           RANK CHECK
        =============================== */
        $userRank = UserRank::where('user_id', $user->id)->first();

        if (! $userRank) {
            return; // no rank achieved yet
        }

        $rank = (int) $userRank->rank;
        if ($rank <= 0 || $rank > 5) return;

        /* ===============================
           DUPLICATE PREVENTION (PER RANK)
        =============================== */
        if (
            UserRankRoiIncomes::where('user_roi_income_id', $this->roiIncome->id)
                ->where('rank', $rank)
                ->exists()
        ) {
            return;
        }

        /* ===============================
           CALCULATE RANK BONUS
           (2% PER RANK)
        =============================== */
        $extraPercent = bcmul((string) $rank, '2', 2); // eg: rank 3 => 6%

        $extraAmount = multipleDecimalStrings(
            $this->roiIncome->income,
            divDecimalStrings($extraPercent, '100', 6),
            2
        );

        if ($extraAmount <= 0) return;

        /* ===============================
           CREDIT PASSIVE (2× SHARED CAP)
        =============================== */
        $credited = IncomeService::creditPassiveIncome(
            $subscription,
            $extraAmount
        );

        if ($credited <= 0) return;

        /* ===============================
           SAVE DAILY RANK ROI
        =============================== */
        UserRankRoiIncomes::create([
            'user_id'            => $user->id,
            'user_roi_income_id' => $this->roiIncome->id,
            'rank'               => $rank,
            'income_percent'     => $extraPercent,
            'income'             => $credited,
        ]);

        /* ===============================
           UPDATE STATS
        =============================== */
        userIncomeStat($user)->increment('rank', $credited);
        userIncomeStat($user)->increment('total', $credited);

        userIncomeOnHold($user)->increment('rank', $credited);
        userIncomeOnHold($user)->increment('total', $credited);

        /* ===============================
           WALLET ENTRY
        =============================== */
        CreateUserWalletLedgerJob::dispatch(
            $user,
            'Income Wallet',
            'INR',
            'Credit',
            $credited,
            "Rank bonus Extra Systematic Bonus Credited"
        )->delay(now()->addSecond());
    }
}
