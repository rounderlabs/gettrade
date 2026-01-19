<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModuleSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ModuleSettingController extends Controller
{
    public function Index()
    {
        return Inertia::render('Admin/Setting/ModuleSetting', [
        ]);
    }

    public function getUserOverview()
    {

//        $data = User::select('id', 'username', 'email', 'mobile')->withSum('userUsdWallet','balance')->withSum('subscriptions', 'amount_in_usd')->withSum('withdrawalHistories', 'amount')->get();
//        return response()->json($data);
        $columns = [
            'UserId',
            'Username',
            'Email',
            'Mobile No.',
            'user_usd_wallet_sum_balance',
            'Subscriptions',
            'withdrawal_histories_sum_amount'
        ];

        // create csv
        return response()->streamDownload(function () use ($columns) {
            $file = fopen('php://output', 'w+');
            fputcsv($file, $columns);
            $data = User::select('id', 'username', 'email', 'mobile')->withSum('userUsdWallet', 'balance')->withSum('subscriptions', 'amount_in_usd')->withSum('withdrawalHistories', 'amount')->where('email_verified_at', '!=', null);
            $data = $data->cursor()
                ->each(function ($data) use ($file) {
                    $data = $data->toArray();
                    fputcsv($file, $data);
                });
            fclose($file);
        }, 'users_Overview_CSV' . date('d-m-Y h:i:s a') . '.csv');

        //        return response()->json($user);
    }
}
