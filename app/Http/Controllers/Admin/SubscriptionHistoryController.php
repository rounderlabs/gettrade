<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\UserIncomeSetting;
use App\Models\UserInvestment;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubscriptionHistoryController extends Controller
{
    public function Index()
    {
        return Inertia::render('Admin/Subscription/Index');
    }

    public function getSubscriptionHistory($plan, $from_date, $to_date)
    {
        $where = [];
        if (is_numeric($plan)) {
            $where = ['plan_id' => strtolower($plan)];
        }
        if ($from_date != 'noDate' && $to_date != 'noDate') {
            $fromDateNew = Carbon::createFromFormat('Y-m-d', $from_date)->format('Y-m-d');
            $fromToNew = Carbon::createFromFormat('Y-m-d', $to_date)->format('Y-m-d');
            $subscriptions = Subscription::with('user', 'plan')
                ->whereDate('created_at', '>=', $fromDateNew)->whereDate('created_at', '<=', $fromToNew)
                ->where($where)
                ->withCasts(['created_at' => 'datetime:Y-m-d'])
                ->orderByDesc('id')->simplePaginate(200);
        } else {
            $subscriptions = Subscription::with('user', 'plan')
                ->where($where)
                ->withCasts(['created_at' => 'datetime:Y-m-d'])
                ->orderByDesc('id')->simplePaginate(200);
        }
        return response()->json($subscriptions);
    }


}
