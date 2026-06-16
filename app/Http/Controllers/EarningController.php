<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\UserLevelIncome;
use App\Models\UserLevelIncomeStat;
use App\Models\UserLevelRoiIncome;
use App\Models\UserRank;
use App\Models\Reward;
use App\Models\UserRewardRank;
use App\Models\UserLegBusiness;
use App\Models\UserRewardIncomeStats;
use Inertia\Inertia;

use App\Http\Controllers\Concerns\DisplaysCurrency;

class EarningController extends Controller
{
    use DisplaysCurrency;
    public function showMonthlyTradingBonus()
    {
        return Inertia::render('Earnings/TradingBonus', []);
    }

    public function getMonthlyTradingBonus()
    {
        $user = auth()->user();

        $monthlyRoi = $user->userRoiIncomes()
            ->with('subscription.user')
            ->orderByDesc('id')
            ->simplePaginate(10);

        $monthlyRoi->getCollection()->transform(function ($row) use ($user) {
            $row->amount_display = $this->displayAmount($row->amount ?? 0, $user);
            $row->income_display = $this->displayAmount($row->income ?? 0, $user);
            return $row;
        });

        return response()->json($monthlyRoi);
    }



    public function showDirectBonus()
    {
        return Inertia::render('Earnings/MarketingBonus', []);
    }

    public function getDirectBonus()
    {
        $user = auth()->user();

        $direct = $user->userDirectIncomes()
            ->with('subscription.user')
            ->orderByDesc('id')
            ->simplePaginate(10);

        $direct->getCollection()->transform(function ($row) use ($user) {
            $row->subscription->amount_display = $this->displayAmount($row->subscription->amount ?? 0, $user);
            $row->income_display = $this->displayAmount($row->income ?? 0, $user);
            return $row;
        });

        return response()->json($direct);
    }



    public function showSystematicBonus()
    {
        return Inertia::render('Earnings/SystematicBonus', [
            'team' => auth()->user()->team,
        ]);
    }

    public function getSystematicBonus()
    {
        $user = auth()->user();

        $levelRoi = $user->userLevelRoiIncomes()
            ->with(['userRoiIncome.user','userRoiIncome.subscription'])
            ->orderByDesc('id')
            ->simplePaginate(10);

        $levelRoi->getCollection()->transform(function ($row) use ($user) {
            $row->income_display = $this->displayAmount($row->income_usd ?? 0, $user);
            return $row;
        });

        return response()->json($levelRoi);
    }





    public function showRankBonus()
    {
        $subscriptions = Subscription::where('user_id', auth()->id())->with('plan')->get();
        $userRank = UserRank::where('user_id', auth()->id())->first();
        return Inertia::render('Earnings/RankBonus', [
            'subscriptions' => $subscriptions,
            'user_rank' => $userRank,
        ]);
    }

    public function getRankBonus()
    {
        $user = auth()->user();

        $rank = $user->userRankRoiIncomes()
            ->with(['userRoiIncome.user','userRoiIncome.subscription'])
            ->orderByDesc('id')
            ->simplePaginate(10);

        $rank->getCollection()->transform(function ($row) use ($user) {
            $row->income_display = $this->displayAmount($row->income ?? 0, $user);
            return $row;
        });

        return response()->json($rank);
    }



    public function showRewardBonus()
    {
        return Inertia::render('Earnings/RewardBonus', []);
    }

    public function getRewardBonus()
    {
        $user = auth()->user();

        // Standard rewards (excluding salary entries)
        $reward = $user->userRewardIncomeStats()
            ->where('reward_text', 'not like', '%salary%')
            ->with('reward')
            ->orderByDesc('id')
            ->simplePaginate(10);

        $reward->getCollection()->transform(function ($row) use ($user) {
            $row->income_display = $this->displayAmount($row->income_usd ?? 0, $user);
            return $row;
        });

        return response()->json($reward);
    }

    public function showSalaryIncome()
    {
        return Inertia::render('Earnings/SalaryBonus', []);
    }

    public function getSalaryIncome()
    {
        $user = auth()->user();

        // Salary rewards (specifically filtering for salary entries)
        $salary = $user->userRewardIncomeStats()
            ->where(function($q) {
                $q->where('reward_text', 'like', '%salary%')
                  ->orWhereHas('reward', function($sub) {
                      $sub->where('salary_amount', '>', 0);
                  });
            })
            ->with('reward')
            ->orderByDesc('id')
            ->simplePaginate(10);

        $salary->getCollection()->transform(function ($row) use ($user) {
            $row->income_display = $this->displayAmount($row->income_usd ?? 0, $user);
            return $row;
        });

        return response()->json($salary);
    }

    public function showRewardsList()
    {
        $user = auth()->user();

        // Calculate matching leg business (lower of greatest leg and sum of other legs)
        $legs = UserLegBusiness::where('user_id', $user->id)->orderByDesc('amount')->get();
        $greatestLeg = $legs->first()?->amount ?? '0.00';
        $otherLegsSum = $legs->skip(1)->sum('amount') ?? '0.00';
        $matchingLegBusiness = bccomp($greatestLeg, $otherLegsSum, 2) <= 0 ? $greatestLeg : $otherLegsSum;

        // Fetch all system rewards
        $allRewards = Reward::orderBy('matching_leg_business', 'asc')->get();

        // Load current rank based on highest achieved UserRewardRank
        $rewardRankRecord = UserRewardRank::where('user_id', $user->id)
            ->where('is_achieved', 1)
            ->orderByDesc('rank')
            ->first();

        $currentRank = 0;
        if ($rewardRankRecord) {
            foreach ($allRewards as $index => $reward) {
                if ($reward->id == $rewardRankRecord->rank) {
                    $currentRank = $index + 1;
                    break;
                }
            }
        }

        // Fetch user's achieved reward ranks
        $achievedRanks = UserRewardRank::where('user_id', $user->id)
            ->where('is_achieved', 1)
            ->pluck('rank')
            ->toArray();

        // Map achieved state, format amounts
        $rewards = $allRewards->map(function ($reward) use ($user, $achievedRanks) {
            $isUnlocked = in_array($reward->id, $achievedRanks);

            return [
                'id' => $reward->id,
                'rank_name' => $reward->rank_name,
                'matching_leg_business' => $reward->matching_leg_business,
                'matching_leg_business_display' => $this->displayAmount($reward->matching_leg_business, $user),
                'reward_amount' => $reward->reward_amount,
                'reward_amount_display' => $this->displayAmount($reward->reward_amount, $user),
                'salary_amount' => $reward->salary_amount,
                'salary_amount_display' => $this->displayAmount($reward->salary_amount, $user),
                'salary_tenure' => $reward->salary_tenure,
                'reward_text' => $reward->reward_text,
                'is_unlocked' => $isUnlocked
            ];
        });

        return Inertia::render('Earnings/RewardsList', [
            'matching_leg_business' => (string)$matchingLegBusiness,
            'matching_leg_business_display' => $this->displayAmount($matchingLegBusiness, $user),
            'current_rank' => $currentRank,
            'rewards' => $rewards
        ]);
    }




}
