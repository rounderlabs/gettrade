<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserDirectIncome;
use App\Models\UserLevelIncome;
use App\Models\UserLevelIncomeStat;
use App\Models\UserLevelRoiIncome;
use App\Models\UserMagicIncomeStats;
use App\Models\UserPoolIncomeStats;
use App\Models\UserRankRoiIncomes;
use App\Models\UserRewardIncomeStats;
use App\Models\UserRoiIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    // All Income Controller
    private function tradingBonusQuery(Request $request)
    {
        $search  = $request->input('search');
        $from    = $request->input('from');
        $to      = $request->input('to');
        $sortBy  = $request->input('sort_by', 'closing_date');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowedSorts = ['closing_date', 'created_at', 'amount', 'id'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'closing_date';
        }

        $query = UserRoiIncome::with([
            'user:id,username,name,email,mobile',
        ]);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($from) {
            $query->whereDate('closing_date', '>=', $from);
        }

        if ($to) {
            $query->whereDate('closing_date', '<=', $to);
        }

        return $query->orderBy($sortBy, $sortDir);
    }

    // ROI
    public function userTradingBonus(){
        return Inertia::render('Admin/Reports/UserTradingBonusReport');
    }

    public function getUserTradingBonus(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search  = $request->input('search');
        $from    = $request->input('from');   // e.g. '2025-01-01'
        $to      = $request->input('to');     // e.g. '2025-01-31'
        $sortBy  = $request->input('sort_by', 'closing_date');
        $sortDir = $request->input('sort_dir', 'desc');

        $q = UserRoiIncome::with([
            'user:id,username,name,email,mobile',
        ]);

        if ($search) {
            $q->whereHas('user', function($sub) use ($search) {
                $sub->where('username','like',"%{$search}%")
                    ->orWhere('name','like',"%{$search}%")
                    ->orWhere('email','like',"%{$search}%");
            });
        }

        if ($from) {
            $q->whereDate('closing_date', '>=', Carbon::parse($from)->toDateString());
        }
        if ($to) {
            $q->whereDate('closing_date', '<=', Carbon::parse($to)->toDateString());
        }

        // Sorting — guard against invalid columns for security if you accept from client
        $allowedSorts = ['closing_date', 'created_at', 'amount', 'id'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'closing_date';
        }
        $q->orderBy($sortBy, $sortDir);

        $result = $q->paginate($perPage);

        return response()->json($result);
    }

//    public function exportUserTradingBonus(Request $request)
//    {
//        $rows = $this->getFilteredQuery($request)->get(); // implement getFilteredQuery similar to above but returning query builder
//
//        $response = new StreamedResponse(function() use ($rows) {
//            $handle = fopen('php://output', 'w');
//            // header
//            fputcsv($handle, ['roi_id','username','name','email','investment_amount','closing_date','roi_amount','roi_income']);
//            foreach ($rows as $r) {
//                fputcsv($handle, [
//                    $r->id,
//                    $r->user->username ?? '',
//                    $r->user->name ?? '',
//                    $r->user->email ?? '',
//                    $r->userInvestment->amount ?? '',
//                    $r->closing_date,
//                    $r->amount,
//                    $r->income,
//                ]);
//            }
//            fclose($handle);
//        });
//
//        $response->headers->set('Content-Type','text/csv');
//        $response->headers->set('Content-Disposition','attachment; filename="user_trading_bonus_'.now()->format('Ymd_His').'.csv"');
//
//        return $response;
//    }

    public function exportUserTradingBonus(Request $request)
    {
        $rows = $this->tradingBonusQuery($request)->get();

        $response = new StreamedResponse(function () use ($rows) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'roi_id',
                'username',
                'name',
                'email',
                'investment_amount',
                'closing_date',
                'roi_amount',
                'roi_income'
            ]);

            foreach ($rows as $r) {
                fputcsv($handle, [
                    $r->id,
                    $r->user->username ?? '',
                    $r->user->name ?? '',
                    $r->user->email ?? '',
                    $r->userInvestment->amount ?? '',
                    $r->closing_date,
                    $r->amount,
                    $r->income,
                ]);
            }

            fclose($handle);

        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="user_trading_bonus_' . now()->format('Ymd_His') . '.csv"'
        );

        return $response;
    }

    // Level ROI

    public function userTradeProfitBonus()
    {
        return Inertia::render('Admin/Reports/UserLevelRoiBonusReport');
    }

    public function getUserTradeProfitBonus(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search  = $request->input('search');
        $from    = $request->input('from');
        $to      = $request->input('to');

        $query = UserLevelRoiIncome::with(['user', 'userRoiIncome'])
            ->orderByDesc('id');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return response()->json($query->paginate($perPage));
    }

    public function exportUserTradeProfitBonus(Request $request)
    {
        $query = UserLevelRoiIncome::with(['user', 'userRoiIncome'])
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $filename = 'user_level_roi_bonus_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['#', 'Username', 'Level', 'Income %', 'Income', 'Created']);

            $i = 1;
            foreach ($query->get() as $record) {
                fputcsv($handle, [
                    $i++,
                    optional($record->user)->username,
                    $record->level,
                    $record->income_percent,
                    $record->income_usd,
                    $record->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function userLevelBonus()
    {
        return Inertia::render('Admin/Reports/UserLevelBonusReport');
    }

    public function getUserLevelBonus(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search  = $request->input('search');
        $from    = $request->input('from');
        $to      = $request->input('to');

        $query = UserLevelIncomeStat::with(['user', 'fromUser', 'subscription','subscription.user'])
            ->orderByDesc('id');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return response()->json($query->paginate($perPage));
    }

    public function exportUserLevelBonus(Request $request)
    {
        $query = UserLevelIncomeStat::with(['user', 'userLevelIncome', 'subscription', 'user_investment'])
            ->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $filename = 'user_level_bonus_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['#', 'User', 'Level Income ID', 'Investment', 'Subscription', 'Amount', 'Created']);

            $i = 1;
            foreach ($query->get() as $record) {
                fputcsv($handle, [
                    $i++,
                    optional($record->user)->username,
                    optional($record->userLevelIncome)->id,
                    optional($record->user_investment)->amount,
                    optional($record->subscription)->amount,
                    $record->amount ?? '-',
                    $record->created_at,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function userPoolBonus()
    {
        return Inertia::render('Admin/Reports/UserPoolBonusReport');
    }


    public function userRewardBonus()
    {
        return Inertia::render('Admin/Reports/UserRewardBonusReport');
    }

    public function getUserRewardBonus(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search  = $request->input('search');
        $from    = $request->input('from');
        $to      = $request->input('to');

        $query = UserRewardIncomeStats::with(['user', 'reward', 'rewardIncomeClosing'])
            ->orderByDesc('id');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return response()->json($query->paginate($perPage));
    }

    public function exportUserRewardBonus(Request $request)
    {
        $query = UserRewardIncomeStats::with(['user', 'reward', 'rewardIncomeClosing'])
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $filename = 'user_reward_bonus_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['#', 'Username', 'Reward Title', 'Income (USD)', 'Reward Text', 'Created']);

            $i = 1;
            foreach ($query->get() as $record) {
                fputcsv($handle, [
                    $i++,
                    optional($record->user)->username,
                    optional($record->reward)->title ?? '-',
                    $record->income_usd,
                    $record->reward_text ?? '-',
                    $record->created_at->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }



    public function userRankIncome()
    {
        return Inertia::render('Admin/Reports/UserRankIncomeReport');
    }

    public function getUserRankIncome(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search  = $request->input('search');
        $from    = $request->input('from');
        $to      = $request->input('to');

        $query = UserRankRoiIncomes::with([
            'user:id,username,name,email',
            'userRoiIncome:id,closing_date'
        ])->orderByDesc('id');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return response()->json($query->paginate($perPage));
    }


    public function exportUserRankIncome(Request $request): StreamedResponse
    {
        $query = UserRankRoiIncomes::with([
            'user:id,username,name,email',
            'userRoiIncome:id,closing_date'
        ])->orderByDesc('id');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                '#',
                'Username',
                'Rank',
                'Income %',
                'Income',
                'ROI Date',
                'Created At'
            ]);

            $i = 1;
            foreach ($query->get() as $row) {
                fputcsv($handle, [
                    $i++,
                    optional($row->user)->username,
                    $row->current_rank,
                    $row->income_percent,
                    $row->income,
                    optional($row->userRoiIncome)->closing_date,
                    $row->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, 'user_rank_income_' . now()->format('Ymd_His') . '.csv');
    }
    public function userDirectBonus()
    {
        return Inertia::render('Admin/Reports/UserDirectBonusReport');
    }

    public function getUserDirectBonus(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search  = $request->input('search');
        $from    = $request->input('from');
        $to      = $request->input('to');

        $query = UserDirectIncome::with([
            'user:id,username,name,email',
            'fromUser:id,username,name'
        ])->orderByDesc('id');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return response()->json($query->paginate($perPage));
    }


    public function exportUserDirectBonus(Request $request): StreamedResponse
    {
        $query = UserDirectIncome::with(['user', 'fromUser'])
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                '#',
                'User',
                'From User',
                'Income',
                'Date'
            ]);

            $i = 1;
            foreach ($query->get() as $row) {
                fputcsv($handle, [
                    $i++,
                    optional($row->user)->username,
                    optional($row->fromUser)->username,
                    $row->income,
                    $row->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, 'user_direct_bonus_' . now()->format('Ymd_His') . '.csv');
    }

    public function showProUserReport()
    {
        return Inertia::render('Admin/Reports/ProUserReport');
    }

    public function getProUserReportData(Request $request)
    {
        $search = $request->input('user_identifier');
        if (empty($search)) {
            return response()->json(['success' => false, 'message' => 'Please enter a Username, Email, or User ID']);
        }

        $user = \App\Models\User::with(['tree.sponsor', 'userStop'])
            ->where('id', $search)
            ->orWhere('username', $search)
            ->orWhere('email', $search)
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }

        $from = $request->input('from_date');
        $to = $request->input('to_date');
        $interval = $request->input('interval', 'daily');

        // 1. Calculate Business Stats
        $directUserIds = \App\Models\User::where('sponsor_id', $user->id)->pluck('id')->toArray();
        $directBusiness = \App\Models\Subscription::whereIn('user_id', $directUserIds)->sum('amount') ?? 0;

        $downlineUserIds = \App\Models\UserLevelStat::where('user_id', $user->id)->pluck('downline_user_id')->toArray();
        $teamBusiness = \App\Models\Subscription::whereIn('user_id', $downlineUserIds)->sum('amount') ?? 0;

        $legs = \App\Models\UserLegBusiness::where('user_id', $user->id)->orderByDesc('amount')->get();
        $greatestLeg = $legs->first()?->amount ?? '0.00';
        $otherLegsSum = $legs->skip(1)->sum('amount') ?? '0.00';
        $matchingBusiness = bccomp($greatestLeg, $otherLegsSum, 2) <= 0 ? $greatestLeg : $otherLegsSum;

        // 2. Calculate Incomes
        $directIncome = UserDirectIncome::where('user_id', $user->id)
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->sum('income') ?? 0;

        $levelIncome = UserLevelIncomeStat::where('user_id', $user->id)
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->sum('income_amount') ?? 0;

        $roiIncome = UserRoiIncome::where('user_id', $user->id)
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->sum('income') ?? 0;

        $levelRoi = UserLevelRoiIncome::where('user_id', $user->id)
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->sum('income_usd') ?? 0;

        $rankRoi = UserRankRoiIncomes::where('user_id', $user->id)
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->sum('income') ?? 0;

        $rewardIncome = UserRewardIncomeStats::where('user_id', $user->id)
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->sum('income_usd') ?? 0;

        $totalEarned = $directIncome + $levelIncome + $roiIncome + $levelRoi + $rankRoi + $rewardIncome;

        // 3. Payouts
        $totalPayouts = \App\Models\WithdrawalHistory::where('user_id', $user->id)
            ->where('status', 'success')
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->sum('amount') ?? 0;

        // 4. Team stats
        $directsCount = count($directUserIds);
        $activeDirectsCount = \App\Models\User::where('sponsor_id', $user->id)
            ->whereHas('subscriptions', fn($q) => $q->where('is_active', true))
            ->count();
        $teamSize = count($downlineUserIds);

        // 5. Generate interval-wise timeline chart data
        $timeline = [];
        $dateFormat = '%Y-%m-%d';
        if ($interval === 'weekly') {
            $dateFormat = '%Y-w%V';
        } elseif ($interval === 'monthly') {
            $dateFormat = '%Y-%m';
        }

        $directsTimeline = UserDirectIncome::where('user_id', $user->id)
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key, SUM(income) as total")
            ->groupBy('date_key')
            ->pluck('total', 'date_key')->toArray();

        $levelsTimeline = UserLevelIncomeStat::where('user_id', $user->id)
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key, SUM(income_amount) as total")
            ->groupBy('date_key')
            ->pluck('total', 'date_key')->toArray();

        $roisTimeline = UserRoiIncome::where('user_id', $user->id)
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key, SUM(income) as total")
            ->groupBy('date_key')
            ->pluck('total', 'date_key')->toArray();

        $levelRoisTimeline = UserLevelRoiIncome::where('user_id', $user->id)
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key, SUM(income_usd) as total")
            ->groupBy('date_key')
            ->pluck('total', 'date_key')->toArray();

        $rankRoisTimeline = UserRankRoiIncomes::where('user_id', $user->id)
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key, SUM(income) as total")
            ->groupBy('date_key')
            ->pluck('total', 'date_key')->toArray();

        $rewardsTimeline = UserRewardIncomeStats::where('user_id', $user->id)
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key, SUM(income_usd) as total")
            ->groupBy('date_key')
            ->pluck('total', 'date_key')->toArray();

        // Merge all keys
        $allKeys = array_unique(array_merge(
            array_keys($directsTimeline),
            array_keys($levelsTimeline),
            array_keys($roisTimeline),
            array_keys($levelRoisTimeline),
            array_keys($rankRoisTimeline),
            array_keys($rewardsTimeline)
        ));
        sort($allKeys);

        foreach ($allKeys as $key) {
            $d = $directsTimeline[$key] ?? 0;
            $l = $levelsTimeline[$key] ?? 0;
            $r = $roisTimeline[$key] ?? 0;
            $lr = $levelRoisTimeline[$key] ?? 0;
            $rk = $rankRoisTimeline[$key] ?? 0;
            $rw = $rewardsTimeline[$key] ?? 0;

            $timeline[] = [
                'date' => $key,
                'direct' => round($d, 2),
                'level' => round($l, 2),
                'roi' => round($r, 2),
                'level_roi' => round($lr, 2),
                'rank_roi' => round($rk, 2),
                'reward' => round($rw, 2),
                'total' => round($d + $l + $r + $lr + $rk + $rw, 2)
            ];
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'sponsor' => $user->tree->sponsor->username ?? 'None',
                'created_at' => \Illuminate\Support\Carbon::parse($user->created_at)->format('Y-m-d'),
                'is_blocked' => $user->userStop->is_blocked ?? false,
            ],
            'business' => [
                'direct_business' => round($directBusiness, 2),
                'team_business' => round($teamBusiness, 2),
                'matching_business' => round($matchingBusiness, 2),
            ],
            'incomes' => [
                'direct' => round($directIncome, 2),
                'level' => round($levelIncome, 2),
                'roi' => round($roiIncome, 2),
                'level_roi' => round($levelRoi, 2),
                'rank_roi' => round($rankRoi, 2),
                'reward' => round($rewardIncome, 2),
                'total' => round($totalEarned, 2),
            ],
            'payouts' => [
                'total' => round($totalPayouts, 2),
            ],
            'team' => [
                'directs' => $directsCount,
                'active_directs' => $activeDirectsCount,
                'team_size' => $teamSize,
            ],
            'timeline' => $timeline
        ]);
    }

    public function teamBulkAction(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action' => 'required|in:stop_income,restart_income,block_team,unblock_team',
        ]);

        $userId = $request->input('user_id');
        $action = $request->input('action');

        // Fetch all downlines
        $downlineUserIds = \App\Models\UserLevelStat::where('user_id', $userId)->pluck('downline_user_id')->toArray();

        if (empty($downlineUserIds)) {
            return response()->json(['success' => false, 'message' => 'No team members found for this user']);
        }

        if ($action === 'stop_income') {
            \App\Models\UserStop::whereIn('user_id', $downlineUserIds)->update([
                'direct' => 1,
                'roi' => 1,
                'roi_on_roi' => 1,
                'rank' => 1,
                'bonanza' => 1,
                'reward' => 1,
                'withdrawal' => 1
            ]);
            $message = 'Successfully stopped all incomes for ' . count($downlineUserIds) . ' team members.';
        } elseif ($action === 'restart_income') {
            \App\Models\UserStop::whereIn('user_id', $downlineUserIds)->update([
                'direct' => 0,
                'roi' => 0,
                'roi_on_roi' => 0,
                'rank' => 0,
                'bonanza' => 0,
                'reward' => 0,
                'withdrawal' => 0
            ]);
            $message = 'Successfully restarted all incomes for ' . count($downlineUserIds) . ' team members.';
        } elseif ($action === 'block_team') {
            \App\Models\UserStop::whereIn('user_id', $downlineUserIds)->update(['is_blocked' => 1]);
            $message = 'Successfully blocked login access for ' . count($downlineUserIds) . ' team members.';
        } elseif ($action === 'unblock_team') {
            \App\Models\UserStop::whereIn('user_id', $downlineUserIds)->update(['is_blocked' => 0]);
            $message = 'Successfully unblocked login access for ' . count($downlineUserIds) . ' team members.';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
