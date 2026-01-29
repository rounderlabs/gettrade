<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\CreateSubscriptionJob;
use App\Jobs\UserDepositFundJob;
use App\Models\DepositTransaction;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserKyc;
use App\Models\UserLevelStat;
use App\Models\UserStop;
use App\Models\UserUsdWallet;
use App\Models\UserUsdWalletTransaction;
use App\Models\WithdrawalHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Ramsey\Uuid\Uuid;


class AdminUserController extends Controller
{

    public function showAllUsersPage()
    {
        return Inertia::render('Admin/Users/Index', [
            'plans' => Plan::all(),
        ]);
    }

    public function getUsers(Request $request)
    {
        $users = User::with(['team', 'tree.sponsor', 'subscriptions', 'userStop'])->withCasts(['created_at' => 'date:Y-m-d'])->orderByDesc('id')->paginate(10);
        return response()->json($users);
    }

    public function searchUser(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);
        return response()->json(User::with(['team', 'tree.sponsor', 'subscriptions'])->where('email', strtolower($request->email))->with('country')->first());
    }

    public function filterUsers(Request $request)
    {
        if ($request->status == 'active') {
            $users = User::whereHas('subscriptions')->with(['team', 'tree.sponsor', 'subscriptions', 'userStop', 'userInvestment'])
                ->withCasts(['created_at' => 'date:Y-m-d'])
                ->orderByDesc('id')->paginate(10);
        } else if ($request->status == 'inactive') {
            $users = User::whereDoesntHave('subscriptions')->with(['team', 'tree.sponsor', 'subscriptions', 'userStop', 'userInvestment'])
                ->withCasts(['created_at' => 'date:Y-m-d'])
                ->orderByDesc('id')->paginate(10);
        } else {
            if (is_numeric($request->filter)) {
                return response()->json(
                    User::with(['team', 'tree.sponsor', 'subscriptions', 'userStop', 'userInvestment'])
                        ->where('id', $request->filter)
                        ->orWhere('username', 'like', '%' . $request->filter)
                        ->withCasts(['created_at' => 'date:Y-m-d'])
                        ->orderByDesc('id')->paginate(50)
                );
            }
            $users = User::with(['team', 'tree.sponsor', 'subscriptions', 'userStop', 'userInvestment'])
                ->where('name', 'like', '%' . $request->filter . '%')
                ->orWhere('email', 'like', '%' . $request->filter . '%')
                ->orWhere('username', 'like', '%' . $request->filter . '%')
                ->orWhere('mobile', 'like', '%' . $request->filter . '%')
                ->orWhere('id', $request->filter)
                ->withCasts(['created_at' => 'date:Y-m-d'])
                ->orderByDesc('id')->paginate(50);
        }
        return response()->json($users);
    }

    public function showUser(User $user)
    {
        $level = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];
        $teamUsers = null;
        $userUsdWallet = userUsdWallet($user);
        $userIncomeWallet = userIncomeWallet($user);
        $userLevel = UserLevelStat::where('user_id', $user->id)->whereIn('level', $level)->pluck('downline_user_id');
        if (!is_null($userLevel)) {
            $teamUsers = $userLevel;
        }
        $teamSubscriptionsAmountSum = 0;
        $selfSubscriptionsAmountSum = 0;
        $userStopIncome = userStop($user);
        $teamWithdrawalAmountSum = 0;
        $selfWithdrawalAmountSum = 0;
        if (!is_null($teamUsers)) {
            $teamSubscriptionsAmountSum = Subscription::whereIn('user_id', $teamUsers)->sum('amount');
            $selfSubscriptionsAmountSum = Subscription::where('user_id', $user->id)->sum('amount');
            $teamWithdrawalAmountSum = WithdrawalHistory::whereIn('user_id', $teamUsers)->sum('amount');
            $selfWithdrawalAmountSum = WithdrawalHistory::where('user_id', $user->id)->sum('amount');
        }
        return Inertia::render('Admin/User/Index', [
            'user' => User::where('id', $user->id)->first(),
            'sponsor' => $user->tree->sponsor,
            'team_subscriptions_amount_count' => castDecimalString($teamSubscriptionsAmountSum ?? '0.00', 2) + castDecimalString($selfSubscriptionsAmountSum, 2),
            'team_withdrawal_amount_count' => castDecimalString($teamWithdrawalAmountSum, 2) + castDecimalString($selfWithdrawalAmountSum ?? '0.00', 2),
            'user_usd_wallet' => $userUsdWallet,
            'user_income_wallet' => $userIncomeWallet,
            'user_stop'=>$userStopIncome
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'email' => ['required', 'email'],
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits_between:10,15'],
        ]);
        $user = User::find($request->user_id);
        $user->update([
            'email' => strtolower($request->email),
            'name' => ucwords($request->name),
            'mobile' => $request->mobile,
        ]);
        return redirect()->route('admin.user.create', [$request->user_id])->with('notification', ['Profile Update Successfully', 'success']);
    }

    public function showWithdrawalHistory()
    {
        return Inertia::render('Admin/Withdrawal/Index');
    }

    public function withdrawalHistory(Request $request)
    {
        $query = WithdrawalHistory::with('user');

        if ($request->filled('status') && strtolower($request->status) !== 'all') {
            $query->where('status', strtolower($request->status));
        }

        if ($request->filled('filter')) {
            $query->where(function ($q) use ($request) {
                $q->where('txn_id', 'like', '%' . $request->filter . '%')
                    ->orWhere('address', 'like', '%' . $request->filter . '%')
                    ->orWhere('id', 'like', '%' . $request->filter . '%');
            });
        }

        $withdrawals = $query->withCasts(['created_at' => 'date:Y-m-d'])
            ->orderByDesc('id')
            ->paginate(50);

        return response()->json($withdrawals);
    }


    public function filterWithdrawalHistory(Request $request)
    {
        $request->validate([
            'filter' => ['required', 'string']
        ]);
        $users = WithdrawalHistory::with(['user'])
            ->where('txn_id', 'like', '%' . $request->filter . '%')
            ->orWhere('address', 'like', '%' . $request->filter . '%')
            ->orWhere('id', 'like', '%' . $request->filter . '%')
            ->withCasts(['created_at' => 'date:Y-m-d'])
            ->orderByDesc('id')->paginate(50);
        return response()->json($users);
    }

    public function statusFilterWithdrawalHistory(Request $request)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in(['pending', 'processing', 'success', 'failed'])]
        ]);
        $users = WithdrawalHistory::with(['user'])
            ->where('status', strtolower($request->status))
            ->withCasts(['created_at' => 'date:Y-m-d'])
            ->orderByDesc('id')->paginate(50);
        return response()->json($users);
    }

    public function processWithdrawal(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:withdrawal_histories,id']
        ]);
        $wh = WithdrawalHistory::find($request->id);
        if ($wh->status == 'processing') {
            $wh->update(['status' => 'pending']);
        }
        $process = Artisan::queue('withdrawal:process', ['withdraw_history_id' => $request->id]);
        if ($process) {
            return response()->json(['message' => 'Withdrawal Successfully processed'], 200);
        } else {
            return response()->json(['message' => 'Withdrawal process failed'], 422);
        }
    }

    public function manualDeposit(Request $request)
    {
        $request->validate([
            'plan_id' => ['required', 'numeric', 'exists:plans,id'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
        ]);


        $user = User::find($request->user_id);
        $plan = Plan::where('id', $request->plan_id)->first();
        if (is_null($plan)) {
            return redirect()->back()->withErrors(['errors' => 'Invalid Plan Selected']);
        }
        $usdDepositAmount = $plan->price;
        $invoice = $user->invoices()->create([
            'plan_id' => $plan->id,
            'amount' => $usdDepositAmount,
            'status' => 'pending'
        ]);
        if (is_null($invoice)) {
            return redirect()->back()->withErrors(['errors', 'Unable to create invoice.']);
        }
        $voucherTransaction = $user->voucherTransactions()->create([
            'txn_id' => Uuid::uuid4(),
            'amount' => $usdDepositAmount,
            'txn_time' => now(),
        ]);

        $depositTransaction = $voucherTransaction->depositTransaction()->create([
            'user_id' => $user->id,
            'address' => '',
            'crypto' => 'voucher',
            'amount' => castDecimalString($usdDepositAmount, 8),
            'txn_time' => $voucherTransaction->txn_time
        ]);

        if ($depositTransaction) {
            $invoice->update([
                'deposit_transaction_id' => $depositTransaction->id,
                'status' => 'success'
            ]);
            dispatch_sync(new UserDepositFundJob($depositTransaction));
        }


        DB::beginTransaction();

        $userUsdWallet = UserUsdWallet::where('user_id', $user->id)->lockForUpdate()->first();
        if (is_null($userUsdWallet)) {
            DB::rollBack();
            return redirect()->back()->with('notification', ['Insufficient Balance', 'danger']);
        }

        if ($userUsdWallet->balance < $usdDepositAmount) {
            DB::rollBack();
            return redirect()->back()->with('notification', ['Insufficient Balance', 'danger']);
        }


        $walletTransaction = UserUsdWalletTransaction::create([
            'user_id' => $user->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd' => subDecimalStrings($usdDepositAmount, $plan->gst),
            'last_amount' => castDecimalString($userUsdWallet->balance, 8),
            'summary' => UserUsdWalletTransaction::TXN_SUMMARY['ACTIVE PLAN'],
            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time' => now()
        ]);
        $userUsdWallet->decrement('balance', $usdDepositAmount);

        if (is_null($walletTransaction)) {
            DB::rollBack();
            return redirect()->back()->with('notification', ['Please try again later', 'danger']);
        }
        DB::commit();

        CreateSubscriptionJob::dispatch($plan, $user, $walletTransaction);
        return redirect()->back()->with('notification', ['Package subscribed successfully', 'success']);

    }

    public function changePassword()
    {
        return Inertia::render('Admin/ChangePassword');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);
        if (Hash::check($request->old_password, Auth()->user()->password)) {
            Auth()->user()->update([
                'password' => $request->password,
            ]);

            Auth::guard('admin')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('admin.change.password')->with('notification', ['Admin password updated Successfully! Please Login again ', 'success']);
        } else {
            return redirect()->back()->with('notification', ['Old Password do not match', 'danger']);
        }
    }


    public function showUserSubscriptions(User $user)
    {
        $subscriptions = $user->subscriptions()->with('plan:id,name')->orderByDesc('id')->paginate(10);
        // dump($subscriptions);die();
        return response()->json($subscriptions);
    }

    public function verifyUserEmail(User $user)
    {
        if (is_null($user)) {
            return back()->with('notification', ['User Not Exist ', 'danger']);
        }
        if (!is_null($user->email_verified_at)) {
            return back()->with('notification', ['User Email Already Verified! ', 'success']);
        }

        $user->forceFill([
            'email_verified_at' => Date::now(),
        ])->save();

        return Redirect::back()->with([
            'data' => 'Email Manually Verified!',
        ]);

    }

    public function loginByUserId(User $user)
    {
        if (is_null($user)) {
            return back()->with('notification', ['User Not Exist ', 'danger']);
        }
//        Auth:: loginUsingId(1);
        Auth::guard('web')->login($user);
        return \redirect()->route('dashboard');

    }

    public function getUserStopRoi(User $user)
    {
        if (is_null($user)) {
            return back()->with('notification', ['User Not Exist ', 'danger']);
        }
        $userStopDetails = userStop($user);
        return response()->json($userStopDetails);

    }



    public function UpdateUserWithdrawalLimit(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'gt:0']
        ]);
        $user = User::find($request->user_id);
        if (is_null($user)) {
            return back()->with('notification', ['User Not Exist ', 'danger']);
        }
        $userWithdrawalLimit = UserWithdrawalLimit::updateOrCreate(['user_id' => $request->user_id], [
            'daily_limit' => castDecimalString($request->amount, 4)
        ]);
        return redirect()->route('admin.users.index')->with('notification', ['User withdrawal limit set successfully !', 'success']);
    }

    public function teamStartStopWithdrawal(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'withdrawal_stop' => ['required', 'boolean'],
        ]);
        UserStop::where('user_id', $request->user_id)->update(['withdrawal' => (int)$request->withdrawal_stop]);
        $this->tenLevelWithdrawalToggle($request->user_id, $request->withdrawal_stop);

//        $userCount = 0;
//        for ($i = 1; $i <= 10; $i++) {
//            $userLevels = UserLevelStat::select('downline_user_id')->where('user_id', $request->user_id)->where('level', $i)->get();
//
//
//            foreach ($userLevels as $userLevel) {
//                $user = User::where('id', $userLevel->downline_user_id)->first();
//                $userStop = userStop($user);
//                $userStop->withdrawal = (int)$request->withdrawal_stop;
//                $userStop->save();
//                $userCount = $userCount++;
//            }
//        }
        return redirect()->route('admin.users.index')->with('notification', ['Team withdrawal stopped successfully!', 'success']);
    }

    public function tenLevelWithdrawalToggle($user_id, $withdrawal_stop)
    {
        $userCount = 0;
        for ($i = 1; $i <= 10; $i++) {
            $userLevels = UserLevelStat::select('downline_user_id')->where('user_id', $user_id)->where('level', $i)->get();
            foreach ($userLevels as $userLevel) {
                $user = User::where('id', $userLevel->downline_user_id)->first();
                $userStop = userStop($user);
                $userStop->withdrawal = (int)$withdrawal_stop;
                $userStop->save();
                $userCount = $userCount++;
                if ($i == 10) {
                    $user_id = $userLevel->downline_user_id;
                    $this->tenLevelWithdrawalToggle($user_id, $withdrawal_stop);
                }
            }
        }
    }

    public function inrDepositRequest()
    {
        return Inertia::render('Admin/Deposit/Index');
    }

    public function getInrDepositRequest()
    {
        $depositRequest = UserFundDepositRequest::with('user')->orderByDesc('id')->simplePaginate(10);
        return response()->json($depositRequest);
    }

    public function getInrDepositRequestById($id)
    {
        return Inertia::render('Admin/Deposit/UserInrDepositRequestByID', [
            'request' => UserFundDepositRequest::find($id),
        ]);
    }

    public function userKycRequest()
    {
        return Inertia::render('Admin/Users/UserKycRequest');
    }

    public function getUserKycRequest()
    {
        $kycRequests = UserKyc::with('user')->orderByDesc('id')->simplePaginate(10);
        return response()->json($kycRequests);
    }

    public function viewKycById($id)
    {
        return Inertia::render('Admin/Users/UserKycDetailsById', [
            'kyc' => UserKyc::find($id),
        ]);
    }

    public function updateKycStatus(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'status' => ['required']
        ]);

        $kyc = UserKyc::find($request->id);
        $kyc->update([
            'kyc_status' => $request->status
        ]);

        return back()->with('notification', ['Kyc Updated Successfully', 'success']);
    }

    public function updateDepositRequest(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'status' => ['required'],
            'is_credited' => ['required']
        ]);

        $depositRequest = UserFundDepositRequest::find($request->id);
        $depositRequest->update([
            'is_credited' => $request->is_credited,
            'status' => $request->status
        ]);
        return back()->with('notification', ['Deposit Request Updated Successfully', 'success']);
    }

    public function showDepositHistory(User $user)
    {
        return Inertia::render('Admin/Deposit/Index', [
            'user' => $user,
            'explorers' => getExplorers(),
        ]);
    }

    public function getUserDeposits(Request $request)
    {
        $query = DepositTransaction::with(['user', 'depositTransactionable', 'depositTransactionable.invoice']);

        // Filter by user_id
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by crypto type
        if ($request->filled('crypto_type') && $request->crypto_type !== 'all') {
            $query->where('currency_symbol', $request->crypto_type);
        }

        // Filter by username
        if ($request->filled('username') && $request->username !== 'noUser') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'LIKE', "%{$request->username}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Optional text search
        if ($request->filled('filter') && $request->filter !== 'noText') {
            $search = $request->filter;
            $query->where(function ($q) use ($search) {
                $q->where('address', 'LIKE', "%{$search}%")
                    ->orWhere('currency_symbol', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($sub) use ($search) {
                        $sub->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('username', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        $data = $query->orderByDesc('id')->paginate(10);

        return response()->json($data);
    }

//    public function showUserCompoundedList()
//    {
//        return Inertia::render('Admin/Users/UserCompoundedList');
//    }

//    public function getUserCompoundedList()
//    {
//        $allCompoundedUser = UserRoiCompounding::with('user')->orderByDesc('id')->simplePaginate(10);
//        return response()->json($allCompoundedUser);
//    }
//
//    public function showUserRefundRequest()
//    {
//        return Inertia::render('Admin/Users/UserRefundRequest');
//    }

//    public function getUserRefundRequest()
//    {
//        $requests = UserRefundSubscription::with('user')->orderByDesc('id')->simplePaginate(10);
//        return response()->json($requests);
//    }

    public function updateUserStops(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'is_blocked' => 'boolean',
            'direct' => 'boolean',
            'roi' => 'boolean',
            'roi_on_roi' => 'boolean',
            'rank' => 'boolean',
            'bonanza' => 'boolean',
            'reward' => 'boolean',
            'withdrawal' => 'boolean',
        ]);

        UserStop::updateOrCreate(
            ['user_id' => $validated['user_id']],
            $validated
        );

        return back()->with('notification', ['User stop settings updated successfully', 'success']);
    }


}
