<?php

namespace App\Http\Controllers;

use App\Models\RankIncome;
use App\Models\RankIncomeStat;
use App\Models\Subscription;
use App\Models\UserLevelIncome;
use App\Models\UserLevelIncomeStat;
use App\Models\UserLevelRoiIncome;
use App\Models\UserNlitenPoolIncome;
use App\Models\UserRank;
use App\Models\UserRoiCompoundedIncomes;
use Inertia\Inertia;

class EarningController extends Controller
{

    public function showMonthlyTradingBonus()
    {
        return Inertia::render('Earnings/TradingBonus', []);
    }

    public function getMonthlyTradingBonus()
    {
        $monthlyRoi = auth()->user()
            ->userRoiIncomes()
            ->with('subscription.user') // ✅ singular
            ->orderByDesc('id')
            ->simplePaginate(10);

        return response()->json($monthlyRoi);
    }

    public function showDirectBonus()
    {
        return Inertia::render('Earnings/MarketingBonus', []);
    }

    public function getDirectBonus()
    {
        $monthlyRoi = auth()->user()
            ->userDirectIncomes()
            ->with('subscription.user') // ✅ singular
            ->orderByDesc('id')
            ->simplePaginate(10);

        return response()->json($monthlyRoi);
    }

    public function showSystematicBonus()
    {
        return Inertia::render('Earnings/SystematicBonus', [
            'team' => auth()->user()->team,
        ]);
    }

    public function getSystematicBonus()
    {
        $levelRoi = auth()->user()
            ->userLevelRoiIncomes()
            ->with([
                'userRoiIncome.user',
                'userRoiIncome.subscription',
            ])
            ->orderByDesc('id')
            ->simplePaginate(10);

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
        $magicBonus = auth()->user()->userRankRoiIncomes()->with([
            'userRoiIncome.user',
            'userRoiIncome.subscription',
        ])->orderby('id', 'desc')->orderByDesc('id')->simplePaginate(10);
        return response()->json($magicBonus);
    }

    public function showRewardBonus()
    {
        return Inertia::render('Earnings/RewardBonus', []);
    }

    public function getRewardBonus()
    {
        $rewardBonus = auth()->user()->userRewardIncomeStats()->orderby('id', 'desc')->orderByDesc('id')->simplePaginate(10);
        return response()->json($rewardBonus);
    }


}
