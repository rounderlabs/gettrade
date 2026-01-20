<?php

namespace App\Jobs;

use App\Methods\IncomeMethods;
use App\Methods\UserLevelMethods;
use App\Methods\UserMethods;
use App\Models\SiteSetting;
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
            $unlockedLevel = $this->getActiveLevel($parentUser->team);
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

            $roiPercent = SiteSetting::getLevelRoiPercent($level);

            if ($roiPercent <= 0) {
                return null;
            }
            $incomePercent = bcmul((string) $roiPercent, '100', 2);
            $incomeAmount = multipleDecimalStrings($amount, (string) $roiPercent);

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

    public function getActiveLevel($activeDirect): int
    {
        // If there is no team data or no active direct info
        if (is_null($activeDirect) || !isset($activeDirect->active_direct)) {
            return 0;
        }

        $count = (int) $activeDirect->active_direct;

        // If less than 10 directs, unlock that level count
        if ($count < 10) {
            return $count * 2;
        }

        // If 5 or more directs, unlock full 10 levels
        return 20;
    }
    private function isUserActive(User $user): bool
    {
        return isUserActive($user);
    }
}
