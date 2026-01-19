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
        return Inertia::render('Admin/Dashboard', [
            'participants' => $topTeam->total,
            'active_participants' => $topTeam->active_total,
            'users' => User::where('id', '>', 0)->count(),
            'total_front_line_bonus'=>0,
            'total_trading_bonus'=>0,
            'total_profit_sharing_bonus'=>0,
            'total_magic_bonus'=>0,
            'total_reward_bonus'=>0,
            'total_pool_bonus'=>0,
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
