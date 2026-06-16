<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserRewardRank;
use App\Models\Reward;
use App\Models\RewardIncomeClosing;
use App\Models\UserRewardIncomeStats;
use App\Jobs\CreateUserWalletLedgerJob;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class PayMonthlySalaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pay-monthly-salary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes monthly salary payouts for achieved reward ranks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting monthly salary payouts processing...');

        // 1. Fetch all achieved reward ranks
        $achievedRanks = UserRewardRank::where('is_achieved', 1)->get();

        if ($achievedRanks->isEmpty()) {
            $this->info('No achieved reward ranks found.');
            return self::SUCCESS;
        }

        // Create/Find closing session for today
        $closing = RewardIncomeClosing::firstOrCreate(
            ['closing_date' => now()->toDateString()],
            ['status' => 'success']
        );

        $creditedCount = 0;

        foreach ($achievedRanks as $userRank) {
            $user = User::find($userRank->user_id);
            if (!$user) {
                continue;
            }

            $reward = Reward::find($userRank->rank);
            if (!$reward) {
                continue;
            }

            // Verify if the reward has a monthly salary configured
            if (bccomp($reward->salary_amount, '0.00', 2) <= 0 || $reward->salary_tenure <= 0) {
                continue;
            }

            // Count existing salary payments for this specific rank/user in user_reward_income_stats
            $existingPaymentsCount = UserRewardIncomeStats::where('user_id', $user->id)
                ->where('reward_id', $reward->id)
                ->where('reward_text', 'like', '%salary%')
                ->count();

            // Check if they already received all payments for this rank
            if ($existingPaymentsCount >= $reward->salary_tenure) {
                continue;
            }

            // Find the last salary payout for this rank
            $lastSalary = UserRewardIncomeStats::where('user_id', $user->id)
                ->where('reward_id', $reward->id)
                ->where('reward_text', 'like', '%salary%')
                ->orderBy('id', 'desc')
                ->first();

            // Calculate if the next salary payout is due (at least 30 days since last payment)
            $isDue = false;
            if (!$lastSalary) {
                // If somehow missed on achievement
                $isDue = true;
            } else {
                $lastPaidDate = Carbon::parse($lastSalary->created_at);
                if ($lastPaidDate->diffInDays(now()) >= 30) {
                    $isDue = true;
                }
            }

            if ($isDue) {
                $nextMonthIndex = $existingPaymentsCount + 1;
                $salaryText = "Monthly Salary (Month {$nextMonthIndex} of {$reward->salary_tenure}) for {$reward->rank_name} Rank";

                // Record the payout
                UserRewardIncomeStats::create([
                    'user_id' => $user->id,
                    'reward_income_closing_id' => $closing->id,
                    'reward_id' => $reward->id,
                    'income_usd' => $reward->salary_amount,
                    'reward_text' => $salaryText,
                ]);

                // Update wallet balances
                userIncomeWallet($user)->increment('balance', $reward->salary_amount);

                $userIncomeStat = userIncomeStat($user);
                $userIncomeStat->increment('reward', $reward->salary_amount);
                $userIncomeStat->increment('total', $reward->salary_amount);

                // Dispatch wallet ledger job
                CreateUserWalletLedgerJob::dispatch(
                    $user,
                    'Income Wallet',
                    'INR',
                    'Credit',
                    $reward->salary_amount,
                    $salaryText
                )->delay(now()->addSecond());

                $creditedCount++;
                $this->info("Credited Month {$nextMonthIndex} salary of ₹{$reward->salary_amount} to User: {$user->username} for Rank: {$reward->rank_name}");
            }
        }

        $this->info("Successfully credited {$creditedCount} salary payouts!");
        return self::SUCCESS;
    }
}
