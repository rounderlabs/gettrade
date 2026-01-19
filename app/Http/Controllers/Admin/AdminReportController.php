<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserLevelIncome;
use App\Models\UserLevelIncomeStat;
use App\Models\UserLevelRoiIncome;
use App\Models\UserMagicIncomeStats;
use App\Models\UserPoolIncomeStats;
use App\Models\UserRewardIncomeStats;
use App\Models\UserRoiIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    // All Income Controller
    public function index(){

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

    public function exportUserTradingBonus(Request $request)
    {
        $rows = $this->getFilteredQuery($request)->get(); // implement getFilteredQuery similar to above but returning query builder

        $response = new StreamedResponse(function() use ($rows) {
            $handle = fopen('php://output', 'w');
            // header
            fputcsv($handle, ['roi_id','username','name','email','investment_amount','closing_date','roi_amount','roi_income']);
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

        $response->headers->set('Content-Type','text/csv');
        $response->headers->set('Content-Disposition','attachment; filename="user_trading_bonus_'.now()->format('Ymd_His').'.csv"');

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

        $query = UserLevelRoiIncome::with(['user', 'userRoiIncome', 'userInvestment'])
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
        $query = UserLevelRoiIncome::with(['user', 'userRoiIncome', 'userInvestment'])
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
            fputcsv($handle, ['#', 'Username', 'Level', 'Income %', 'Income (USD)', 'Created']);

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

    public function getUserPoolBonus(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search  = $request->input('search');
        $from    = $request->input('from');
        $to      = $request->input('to');

        $query = UserPoolIncomeStats::with(['user', 'poolIncomeClosing'])
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

    public function exportUserPoolBonus(Request $request)
    {
        $query = UserPoolIncomeStats::with(['user', 'poolIncomeClosing'])
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

        $filename = 'user_pool_bonus_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['#', 'Username', 'Pool Closing ID', 'Income %', 'Income (USD)', 'Created']);

            $i = 1;
            foreach ($query->get() as $record) {
                fputcsv($handle, [
                    $i++,
                    optional($record->user)->username,
                    optional($record->poolIncomeClosing)->id,
                    $record->income_percent,
                    $record->income_usd,
                    $record->created_at->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function userMagicBonus()
    {
        return Inertia::render('Admin/Reports/UserMagicBonusReport');
    }

    public function getUserMagicBonus(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search  = $request->input('search');
        $from    = $request->input('from');
        $to      = $request->input('to');

        $query = UserMagicIncomeStats::with(['user', 'magicIncomeClosing'])
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

    public function exportUserMagicBonus(Request $request)
    {
        $query = UserMagicIncomeStats::with(['user', 'magicIncomeClosing'])
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

        $filename = 'user_magic_bonus_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['#', 'Username', 'Magic Closing ID', 'Income %', 'Income (USD)', 'Created']);

            $i = 1;
            foreach ($query->get() as $record) {
                fputcsv($handle, [
                    $i++,
                    optional($record->user)->username,
                    optional($record->magicIncomeClosing)->id,
                    $record->income_percent,
                    $record->income_usd,
                    $record->created_at->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
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


}
