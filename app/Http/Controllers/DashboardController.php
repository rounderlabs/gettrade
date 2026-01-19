<?php

namespace App\Http\Controllers;


use App\Jobs\CreateUserRefundJob;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DashboardController extends Controller
{
    public function showMenu()
    {
        return Inertia::render('Menu/Menu');
    }

    public function showDashboard()
    {

        $qr_url= route('register', ['ref_code' => auth()->user()->username]);
        $totalWithdrawal = WithdrawalHistory::where('user_id', auth()->id())->where('status', '=','success')->sum('amount');
        return Inertia::render('Dashboards/UserDashboard', [
            'team' => auth()->user()->team,
            'user_income_stats' => userIncomeStat(auth()->user()),
            'user_income_on_hold' => userIncomeOnHold(auth()->user()),
            'user_income_wallet' => userIncomeWallet(auth()->user()),
            'user_usd_wallet' => userUsdWallet(auth()->user()),
            'user' => auth()->user(),
            'subscriptions' => auth()->user()->subscriptions()->where('is_active', 1)->with('plan')->orderByDesc('id')->first(),
            'investment' => auth()->user()->subscriptions()->where('is_active', 1)->sum('amount'),
            'total_withdrawal' => $totalWithdrawal,
            'active_subscription' => auth()->user()->subscriptions()->with('plan:id,name')->where('is_active', 1)->orderByDesc('id')->first(),
            'ref_qr' => base64_encode(QrCode::size(250)->generate($qr_url)),
            'showWelcomeModal' => $this->shouldShowWelcomeModal(auth()->user()),
            'welcomeMode' => auth()->user()->welcome_mode,
        ]);
    }

    public function maintenance()
    {
        return Inertia::render('Maintenance/Maintenance');
    }

    public function updateWelcomePreference(Request $request)
    {
//        $request->validate([
//            'preference' => 'required|in:every_login,weekly,monthly,never',
//        ]);

        $user = auth()->user();

        $user->update([
//            'welcome_mode' => $request->preference,
            'welcome_mode' => 'never',
            'welcome_seen_at' => now(),
        ]);

        return redirect()->back()->with('notification', ['Welcome message has been sent.','success']);
    }

    protected function shouldShowWelcomeModal($user): bool
    {
        if ($user->welcome_mode === 'never') {
            return false;
        }

        if (!$user->welcome_seen_at) {
            return true; // first login
        }

        return match ($user->welcome_mode) {
            'every_login' => true,
            'weekly'  => now()->diffInDays($user->welcome_seen_at) >= 7,
            'monthly' => now()->diffInDays($user->welcome_seen_at) >= 30,
            default => false,
        };
    }


}
