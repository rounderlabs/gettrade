<?php

namespace App\Jobs;

use App\Methods\UserLevelMethods;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\UserLevelRoiIncome;
use App\Models\UserRoiIncome;
use App\Services\Income\IncomeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateLevelRoiIncomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private UserRoiIncome $userRoiIncome;
    private User $baseUser;

    public function __construct(UserRoiIncome $userRoiIncome)
    {
        $this->onQueue('income');
        $this->userRoiIncome = $userRoiIncome->withoutRelations();
        $this->baseUser = $userRoiIncome->user;
    }

    public function handle(): void
    {

        $baseIncome = castDecimalString($this->userRoiIncome->income, 4);

        UserLevelMethods::init($this->baseUser)
            ->eachSponsorV1(function (User $parentUser, int $level) use ($baseIncome) {

                /* ========= SAFETY ========= */
                if ($level > 20) {
                    return null;

                }

                if (!haveActiveSubscriptions($parentUser)) {
                    return null;
                }

                $userStop = userStop($parentUser);
                if ($userStop->is_blocked || $userStop->roi_on_roi) {
                    return null;
                }

                /* ========= LEVEL UNLOCK ========= */
                if ($level > $this->getUnlockedLevel($parentUser)) {
                    return null;
                }

                /* ========= DUPLICATE CHECK ========= */
                if (
                    UserLevelRoiIncome::where('user_id', $parentUser->id)
                        ->where('user_roi_income_id', $this->userRoiIncome->id)
                        ->exists()
                ) {
                    return null;
                }

                /* ========= PERCENT ========= */
                $roiPercent = SiteSetting::getLevelRoiPercent($level);
                if ($roiPercent <= 0) return null;

                $incomeAmount = multipleDecimalStrings(
                    $baseIncome,
                    (string)$roiPercent,
                    4
                );

                /* ========= PICK ACTIVE SUBSCRIPTION ========= */
                $subscription = $parentUser->subscriptions()
                    ->where('is_active', true)
                    ->oldest()
                    ->first();


                /* ========= CREDIT PASSIVE (2× CAP) ========= */
                $credited = IncomeService::creditPassiveIncome(
                    $subscription,
                    $incomeAmount
                );

                if ($credited <= 0) return null;


                /* ========= STORE ========= */
                UserLevelRoiIncome::create([
                    'user_id' => $parentUser->id,
                    'user_roi_income_id' => $this->userRoiIncome->id,
                    'level' => $level,
                    'income_percent' => bcmul((string)$roiPercent, '100', 2),
                    'income_usd' => $credited,
                ]);


                /* ========= STATS ========= */
                userIncomeStat($parentUser)->increment('roi_on_roi', $credited);
                userIncomeStat($parentUser)->increment('total', $credited);

                userIncomeOnHold($parentUser)->increment('roi_on_roi', $credited);
                userIncomeOnHold($parentUser)->increment('total', $credited);

                /* ========= WALLET ========= */
                CreateUserWalletLedgerJob::dispatch(
                    $parentUser,
                    'Income Wallet',
                    'INR',
                    'Credit',
                    $credited,
                    "Systematic Bonus from Level-{$level} credited"
                )->delay(now()->addSecond());

                return null;
            });
    }

    private function getUnlockedLevel(User $user): int
    {
        if ($user->manual_unlocked_level > 0) {
            return (int) $user->manual_unlocked_level;
        }

        $directs = (int) ($user->team->active_direct ?? 0);

        return min($directs * 2, 20);
    }
}

