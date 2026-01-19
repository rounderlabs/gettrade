<?php

namespace App\Jobs;

use App\Methods\IncomeMethods;
use App\Methods\UserLevelMethods;
use App\Models\Subscription;
use App\Models\Team;
use App\Models\User;
use App\Models\UserLevelIncomeStat;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateLevelIncomeJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    private Subscription $subscription;


    public function __construct(Subscription $subscription)
    {
        $this->onQueue('income');
        $this->subscription = $subscription;
        $this->user = $subscription->user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        UserLevelMethods::init($this->user)->eachSponsorV1(function ($parentUser, $level) {
            if ($level > 13) {
                return true;
            }
            $unlockedLevel = $this->getActiveLevel($parentUser);
            if ($level > $unlockedLevel) {
                return null;
            }
            $userStop = userStop($parentUser);
            if ($userStop->is_blocked) {
                return null;
            }

            if ($userStop->level) {
                return null;
            }
            if (!$this->isUserActive($parentUser)) {
                return null;
            }
            if (!haveActiveSubscriptions($parentUser)) {
                return null;
            }

            $levelPercent = '0.00';
            if ($level === 1) {
                $levelPercent = '0.05';
                UpdateUserDailyBusinessJob::dispatch($parentUser, 'direct_business',$this->subscription->amount )->delay(now()->addSeconds(10));
            } elseif ($level > 1) {
                $levelPercent = '0.004';
            }

            if ($levelPercent == '0.00') {
                return null;
            }
            $incomeReceived = multipleDecimalStrings($this->subscription->amount, $levelPercent, 4);

            UserLevelIncomeStat::create([
                'user_id' => $parentUser->id,
                'subscription_id' => $this->subscription->id,
                'level' => $level,
                'income_percent' => multipleDecimalStrings($levelPercent, 100, 2),
                'income_amount' => $incomeReceived
            ]);


            $userIncomeWallet = userIncomeOnHold($parentUser);
            $userIncomeWallet->increment('level', $incomeReceived);

            $userIncomeStat = userIncomeStat($parentUser);
            $userIncomeStat->increment('level', $incomeReceived);
            $userIncomeStat->increment('total', $incomeReceived);

            $amount = $incomeReceived;
            $walletType = 'Income Wallet';
            $currency = 'INR';
            $txn_type = 'Credit';
            $remark = 'Level Bonus of ₹ ' . $amount . ' has been Credited';
            CreateUserWalletLedgerJob::dispatch($parentUser, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());

            return null;
        });
    }

    private function isUserActive(User $user): bool
    {
        return isUserActive($user);
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

}
