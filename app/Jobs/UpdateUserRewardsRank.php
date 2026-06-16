<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Reward;
use App\Models\UserRewardRank;
use App\Models\UserLegBusiness;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\RewardIncomeClosing;
use App\Models\UserRewardIncomeStats;
use App\Jobs\CreateUserWalletLedgerJob;

class UpdateUserRewardsRank implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user->withoutRelations();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;

        // Fetch user leg businesses ordered by amount descending
        $legs = UserLegBusiness::where('user_id', $user->id)->orderByDesc('amount')->get();

        $greatestLeg = $legs->first()?->amount ?? '0.00';
        $otherLegsSum = $legs->skip(1)->sum('amount') ?? '0.00';

        // Matching leg business is the lower of the greatest leg and sum of other legs
        $matchingLegBusiness = bccomp($greatestLeg, $otherLegsSum, 2) <= 0 ? $greatestLeg : $otherLegsSum;

        // Fetch all system rewards, ordered by business requirement ascending
        $rewards = Reward::orderBy('matching_leg_business', 'asc')->get();

        // Evaluate each reward milestone
        foreach ($rewards as $reward) {
            // Check if user meets the business requirement
            if (bccomp($matchingLegBusiness, $reward->matching_leg_business, 2) >= 0) {
                // Check if user has already achieved this rank
                $exists = UserRewardRank::where('user_id', $user->id)
                    ->where('rank', $reward->id)
                    ->where('is_achieved', 1)
                    ->exists();

                if (!$exists) {
                    // Update or create the record to mark it as achieved
                    UserRewardRank::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'rank' => $reward->id,
                        ],
                        [
                            'is_achieved' => 1,
                            'is_credited' => 1,
                        ]
                    );

                    // Create/Find closing session
                    $closing = RewardIncomeClosing::firstOrCreate(
                        ['closing_date' => now()->toDateString()],
                        ['status' => 'success']
                    );

                    // 1. Credit the instant reward amount
                    if (bccomp($reward->reward_amount, '0.00', 2) > 0) {
                        UserRewardIncomeStats::create([
                            'user_id' => $user->id,
                            'reward_income_closing_id' => $closing->id,
                            'reward_id' => $reward->id,
                            'income_usd' => $reward->reward_amount,
                            'reward_text' => $reward->reward_text ?? ($reward->rank_name . ' Reward Unlocked'),
                        ]);

                        userIncomeWallet($user)->increment('balance', $reward->reward_amount);

                        $userIncomeStat = userIncomeStat($user);
                        $userIncomeStat->increment('reward', $reward->reward_amount);
                        $userIncomeStat->increment('total', $reward->reward_amount);

                        CreateUserWalletLedgerJob::dispatch(
                            $user,
                            'Income Wallet',
                            'INR',
                            'Credit',
                            $reward->reward_amount,
                            $reward->reward_text ?? ($reward->rank_name . ' Reward Unlocked')
                        )->delay(now()->addSecond());
                    }

                    // 2. Credit the 1st month's salary immediately (if salary_amount > 0 and salary_tenure > 0)
                    if (bccomp($reward->salary_amount, '0.00', 2) > 0 && $reward->salary_tenure > 0) {
                        $salaryText = "Monthly Salary (Month 1 of {$reward->salary_tenure}) for {$reward->rank_name} Rank";
                        UserRewardIncomeStats::create([
                            'user_id' => $user->id,
                            'reward_income_closing_id' => $closing->id,
                            'reward_id' => $reward->id,
                            'income_usd' => $reward->salary_amount,
                            'reward_text' => $salaryText,
                        ]);

                        userIncomeWallet($user)->increment('balance', $reward->salary_amount);

                        $userIncomeStat = userIncomeStat($user);
                        $userIncomeStat->increment('reward', $reward->salary_amount);
                        $userIncomeStat->increment('total', $reward->salary_amount);

                        CreateUserWalletLedgerJob::dispatch(
                            $user,
                            'Income Wallet',
                            'INR',
                            'Credit',
                            $reward->salary_amount,
                            $salaryText
                        )->delay(now()->addSecond());
                    }
                }
            }
        }
    }
}
