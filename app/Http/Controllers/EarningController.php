<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\UserLevelIncome;
use App\Models\UserLevelIncomeStat;
use App\Models\UserLevelRoiIncome;
use App\Models\UserRank;
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

        $reward = $user->userRewardIncomeStats()
            ->orderByDesc('id')
            ->simplePaginate(10);

        $reward->getCollection()->transform(function ($row) use ($user) {
            $row->income_display = $this->displayAmount($row->income ?? 0, $user);
            return $row;
        });

        return response()->json($reward);
    }




}
