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

    public function getSubscriptionHistory(Request $request)
    {
        $query = Subscription::with(['user', 'plan'])
            ->orderByDesc('id');

        // ✅ User Filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // ✅ Plan Filter
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        // ✅ Date Filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . " 00:00:00",
                $request->to_date . " 23:59:59",
            ]);
        }

        return response()->json(
            $query->simplePaginate(10)
        );
    }


}
