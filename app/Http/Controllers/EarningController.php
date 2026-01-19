<?php

namespace App\Http\Controllers;

use App\Models\RankIncome;
use App\Models\RankIncomeStat;
use App\Models\Subscription;
use App\Models\UserLevelIncome;
use App\Models\UserLevelIncomeStat;
use App\Models\UserLevelRoiIncome;
use App\Models\UserNlitenPoolIncome;
use App\Models\UserRoiCompoundedIncomes;
use Inertia\Inertia;

class EarningController extends Controller
{

    public function showFrontLineBonus()
    {
        return Inertia::render('Earnings/FrontLineBonus', []);
    }

    public function getFrontLineBonus()
    {
        $directEarnings = auth()->user()->userLevelIncomeStats()->with('subscription.user')->withCasts(['created_at' => 'datetime:Y-m-d'])->orderByDesc('id')->simplePaginate(10);
        return response()->json($directEarnings);
    }

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

    public function showProfitSharingBonus()
    {
        return Inertia::render('Earnings/ProfitSharingBonus', [
            'team' => auth()->user()->team,
        ]);
    }

    public function getProfitSharingBonus()
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


    public function getDownLineRoiIncome($level = 1)
    {
        $user = auth()->user();
        $incomeByLevel = UserLevelRoiIncome::where('user_id', $user->id)->where('level', $level)->orderByDesc('id')->get();
        return Inertia::render('Earnings/DownLineLevelROIDetails', [
            'incomes' => $incomeByLevel,
        ]);
    }





    public function showPoolBonus()
    {
        return Inertia::render('Earnings/PoolBonus', []);
    }

    public function getPoolBonus()
    {
        $poolBonus = auth()->user()->userPoolIncomeStats()->orderby('id', 'desc')->orderByDesc('id')->simplePaginate(10);
        return response()->json($poolBonus);
    }

    public function showMagicBonus()
    {
        return Inertia::render('Earnings/MagicBonus', []);
    }

    public function getMagicBonus()
    {
        $magicBonus = auth()->user()->userMagicIncomeStats()->orderby('id', 'desc')->orderByDesc('id')->simplePaginate(10);
        return response()->json($magicBonus);
    }

    public function showMaturityBonus()
    {
        $subscriptions = Subscription::where('user_id', auth()->id())->with('plan')->get();
        return Inertia::render('Earnings/MaturityBonus', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function getMaturityBonus()
    {
        $magicBonus = auth()->user()->userMagicIncomeStats()->orderby('id', 'desc')->orderByDesc('id')->simplePaginate(10);
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
