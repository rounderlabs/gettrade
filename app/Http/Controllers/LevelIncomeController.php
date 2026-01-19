<?php

namespace App\Http\Controllers;

use App\Models\RankIncome;
use App\Models\UserLevelIncome;
use Inertia\Inertia;

class LevelIncomeController extends Controller
{

    public function showRankIncomeStat(RankIncome $rankIncome)
    {
        $rankIncomeStats = $rankIncome->rankIncomeStats()->with(['userUsdWalletTransaction:id,amount_in_usd,user_id,txn_time', 'userUsdWalletTransaction.user:id,ref_code,name'])->orderBy('level')->get();
        return Inertia::render('Earnings/PerformanceEarningShow', [
            'earning_stats' => $rankIncomeStats,
            'stat_date' => $rankIncome->income_date->format('F j, Y')
        ]);
    }
}
