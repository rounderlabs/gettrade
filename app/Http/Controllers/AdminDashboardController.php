<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDashboardController extends Controller
{
    public function menu()
    {
        return Inertia::render('Admin/AdminMenu');
    }
    public function showDashboard()
    {
        $topTeam = Team::where('user_id', 1)->first();

        $incomeTotals = DB::table('user_income_stats')
            ->selectRaw('
            COALESCE(SUM(direct),0) as total_direct_bonus,
            COALESCE(SUM(roi),0) as total_trading_bonus,
            COALESCE(SUM(roi_on_roi),0) as total_systematic_bonus,
            COALESCE(SUM(rank),0) as total_rank_bonus
        ')
            ->first();

        return Inertia::render('Admin/Dashboard', [
            'participants' => $topTeam?->total ?? 0,
            'active_participants' => $topTeam?->active_total ?? 0,
            'users' => User::count(),

            'total_direct_bonus'     => (float) $incomeTotals->total_direct_bonus,
            'total_trading_bonus'    => (float) $incomeTotals->total_trading_bonus,
            'total_systematic_bonus' => (float) $incomeTotals->total_systematic_bonus,
            'total_rank_bonus'       => (float) $incomeTotals->total_rank_bonus,
        ]);
    }

    public function exportTeam(User $user): StreamedResponse
    {
        // set header
        $columns = [
            'UserId',
            'Username',
            'Email',
            'Mobile No.',
            'Level',
            'Business',
        ];

        // create csv
        return response()->streamDownload(function () use ($columns, $user) {
            $file = fopen('php://output', 'w+');
            fputcsv($file, $columns);

            $data = User::leftJoin('user_level_stats', 'users.id', '=', 'user_level_stats.downline_user_id')
                ->leftJoin('user_businesses', 'users.id', '=', 'user_businesses.user_id')
                ->select(DB::raw('users.id,users.username,users.email,users.mobile,user_level_stats.level,user_businesses.usd'))
                ->where('user_level_stats.user_id', $user->id);
            $data = $data->cursor()
                ->each(function ($data) use ($file) {
                    $data = $data->toArray();
                    fputcsv($file, $data);
                });
            fclose($file);
        }, $user->name . '_TeamCSV' . date('d-m-Y h:i:s a') . '.csv');
    }

    public function getTeamSubscriptions(User $user)
    {
        // set header
        $columns = [
            'UserId',
            'Username',
            'Email',
            'Mobile No.',
            'Level',
            'Subscriptions',
        ];

        // create csv
        return response()->streamDownload(function () use ($columns, $user) {
            $file = fopen('php://output', 'w+');
            fputcsv($file, $columns);
            $data = User::leftJoin('user_level_stats', 'users.id', '=', 'user_level_stats.downline_user_id')
                ->leftJoin('subscriptions', 'users.id', '=', 'subscriptions.user_id')
                ->select(DB::raw('users.id,users.username,users.email,users.mobile,user_level_stats.level'))
                ->where('user_level_stats.user_id', $user->id);
            $data = $data->cursor()
                ->each(function ($data) use ($file) {
                    $data = $data->toArray();
                    fputcsv($file, $data);
                });
            fclose($file);
        }, $user->name . '_TeamCSV' . date('d-m-Y h:i:s a') . '.csv');
    }


    public function assignRole(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->syncRoles($request->roles);
        return back()->with('success', 'Roles updated for admin.');
    }

}
