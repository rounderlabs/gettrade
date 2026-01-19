<?php

namespace App\Jobs;

use App\Methods\IncomeMethods;
use App\Methods\UserLevelMethods;
use App\Methods\UserMethods;
use App\Models\User;
use App\Models\UserInvestment;
use App\Models\UserLevelRoiIncome;
use App\Models\UserRoiIncome;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateLevelRoiIncomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private UserRoiIncome $userRoiIncome;
    /**
     * @var mixed
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserRoiIncome $userRoiIncome)
    {
        $this->userRoiIncome = $userRoiIncome;
        $this->user = $this->userRoiIncome->user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        UserLevelMethods::init($this->user)->eachSponsorV1(function ($parentUser, $level)  {
            if ($level > 13) {
                return true;
            }
            $unlockedLevel = $this->getActiveLevel($parentUser);
            if ($level > $unlockedLevel) {
                return null;
            }
            if (!$this->isUserActive($parentUser)) {
                return null;
            }
            $userStop = userStop($parentUser);
            if ($userStop->is_blocked) {
                return null;
            }

            if ($userStop->roi_on_roi) {
                return null;
            }
            if (!haveActiveSubscriptions($parentUser)) {
                return null;
            }
            $amount = castDecimalString($this->userRoiIncome->income, 4);
            if ($level == 1){
                $roiPercent = '0.10';
                $incomePercent = '10';
            }else {
                $roiPercent = '0.04';
                $incomePercent = '4';
            }
            $incomeAmount = multipleDecimalStrings($amount, $roiPercent);

            if (UserLevelRoiIncome::where('user_id', $parentUser->id)->where('user_roi_income_id', $this->userRoiIncome->id)->exists()) {
                return null;
            }
            if (!is_null($parentUser->userRefundSubscription)) {
                if ($parentUser->userRefundSubscription->status != 'canceled') {
                    return null;
                }
            }

            UserLevelRoiIncome::create([
                'user_id' => $parentUser->id,
                'user_roi_income_id' => $this->userRoiIncome->id,
                'level' => $level,
                'income_percent' => $incomePercent,
                'income_usd' => $incomeAmount,
            ]);

            $incomeStats = userIncomeStat($parentUser);
            $incomeStats->increment('roi_on_roi', $incomeAmount);
            $incomeStats->increment('total', $incomeAmount);

            $userIncomeOnHold = userIncomeOnHold($parentUser);
            $userIncomeOnHold->increment('roi_on_roi', $incomeAmount);
            $userIncomeOnHold->increment('total', $incomeAmount);


            $amount = $incomeAmount;
            $walletType = 'Income Wallet';
            $currency = 'INR';
            $txn_type = 'Credit';
            $remark = 'Level On Dividend Bonus of ₹ '.$amount.' has been Credited';
            CreateUserWalletLedgerJob::dispatch($parentUser, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());
            return null;
        });
    }

    public function getActiveLevel(User $user): int
    {
        // Levels 1–3 always open
        $openLevel = 3;

        /**
         * STEP 1: Level 1 check → unlock 4–6
         */
        $level1Users = $this->getActiveUsersAtLevel($user->id, 1);

        if ($level1Users->count() < 3) {
            return $openLevel;
        }

        $openLevel = 6;

        /**
         * STEP 2: Level 2 check → unlock 7–9
         * Each level-1 user must have 3 active level-2 users
         */
        $qualifiedLevel2Users = collect();

        foreach ($level1Users as $l1) {
            $level2 = $this->getActiveUsersAtLevel($l1->downline_user_id, 1);

            if ($level2->count() < 3) {
                return $openLevel;
            }

            $qualifiedLevel2Users = $qualifiedLevel2Users->merge($level2);
        }

        $openLevel = 9;

        /**
         * STEP 3: Level 3 check → unlock 10–13
         * Each level-2 user must have 3 active level-3 users
         */
        foreach ($qualifiedLevel2Users as $l2) {
            $level3 = $this->getActiveUsersAtLevel($l2->downline_user_id, 1);

            if ($level3->count() < 3) {
                return $openLevel;
            }
        }

        return 13;
    }

    private function getActiveUsersAtLevel(int $userId, int $level)
    {
        return \DB::table('user_level_stats as uls')
            ->join('subscriptions as s', 's.user_id', '=', 'uls.downline_user_id')
            ->where('uls.user_id', $userId)
            ->where('uls.level', $level)
            ->where('s.is_active', '=', 1)
            ->select('uls.downline_user_id')
            ->get();
    }

    private function isUserActive(User $user): bool
    {
        return isUserActive($user);
    }
}
