<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
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
        return Inertia::render("Admin/Subscription/Index", [
            "plans" => Plan::orderBy("amount", 'ASC')->get()
        ]);
    }

    public function getSubscriptionHistory(Request $request)
    {
        $query = Subscription::with(['user', 'plan'])
            ->orderByDesc('id');

        /* ===========================
           FILTER: PLAN
        =========================== */
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        /* ===========================
           FILTER: USERNAME
        =========================== */
        if ($request->filled('username')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->username . '%');
            });
        }

        /* ===========================
           FILTER: DATE RANGE
        =========================== */
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereDate('created_at', '>=', $request->from)
                ->whereDate('created_at', '<=', $request->to);
        }

        return response()->json(
            $query->simplePaginate(20)
        );
    }



}
