<?php

namespace App\Http\Controllers;

use App\Jobs\CreateSubscriptionJob;
use App\Jobs\CreateUserWalletLedgerJob;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserFundAddRequest;
use App\Models\UserUsdWalletTransaction;
use App\Models\UserWalletLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $depositHistory = UserFundAddRequest::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        return Inertia::render('Wallet/Index', [
            'user_usd_wallet' => userUsdWallet($user),
            'user_income_wallet' => userIncomeWallet($user),
            'deposit_histories' => $depositHistory,

        ]);
    }

    public function showFundTransferForm()
    {
        return Inertia::render('Wallet/FundTransferFrom');
    }

    public function submitFundTransferFrom(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'gte:20'],
            'username' => ['required', 'string', 'exists:users,username',]
        ]);
        $fromUser = auth()->user();
        $toUser = User::where('username', $request->username)->first();
        $fromUserUsdWallet = userUsdWallet($fromUser);
        $toUserUsdWallet = userUsdWallet($toUser);
        if ($request->amount > $fromUserUsdWallet->balance) {
            return redirect()->back()->with('notification', ['Insufficient Fund.', 'danger']);
        }
        $fromUserUsdWallet->decrement('balance', $request->amount);
        $summaryFrom = '₹ ' . $request->amount . ' Fund Transferred To ' . $toUser->username;
        $fromWalletTransaction = UserUsdWalletTransaction::create([
            'user_id' => $fromUser->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd' => $request->amount,
            'last_amount' => $toUserUsdWallet->balance,
            'summary' => $summaryFrom,
            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time' => now()
        ]);
        $amount = $request->amount;
        $walletType = 'USDT Wallet';
        $currency = 'INR';
        $txn_type = 'Debit';
        $remark = $summaryFrom;

        CreateUserWalletLedgerJob::dispatch($fromUser, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());


        $summaryTo = '₹ ' . $request->amount . ' Fund Received From ' . $fromUser->username;
        $toWalletTransaction = UserUsdWalletTransaction::create([
            'user_id' => $request->user()->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['CREDIT'],
            'amount_in_usd' => $request->amount,
            'last_amount' => $toUserUsdWallet->balance,
            'summary' => $summaryTo,
            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time' => now()
        ]);
        $toUserUsdWallet->increment('balance', $request->amount);
        $txn_type = 'Credit';
        $remark = $summaryTo;

        CreateUserWalletLedgerJob::dispatch($fromUser, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());


        return redirect()->back()->with('notification', ['Funds Transferred Successfully.', 'success']);
    }


    public function showActivateMemberForm()
    {
        $plans = Plan::where('is_active', 1)->orderBy('id', 'ASC')->get();
        return Inertia::render('Wallet/ActivateMemberFrom', [
            'plans' => $plans
        ]);
    }

    public function submitActivationForm(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'exists:users,username',],
            'plan_id' => ['required'],
            'amount' => ['required', 'numeric', 'gte:50'],
        ]);
        $plan = Plan::find($request->plan_id);
        $amount = $plan->amount;

        $fromUser = auth()->user();
        $toUser = User::where('username', $request->username)->first();
        $fromUserUsdWallet = userUsdWallet($fromUser);
        $toUserUsdWallet = userUsdWallet($toUser);

        if ($amount > $fromUserUsdWallet->balance) {
            return back()->withErrors([
                'amount' => 'Insufficient wallet balance'
            ]);
        }


        $fromUserUsdWallet->decrement('balance', $amount);
        $summaryFrom = '₹ ' . $amount . ' Fund Transferred To ' . $toUser->username . ' Used for Subscription';
        $fromWalletTransaction = UserUsdWalletTransaction::create([
            'user_id' => $fromUser->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd' => $amount,
            'last_amount' => $fromUserUsdWallet->balance,
            'summary' => $summaryFrom,
            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time' => now()
        ]);
        $walletType = 'USDT Wallet';
        $currency = 'INR';
        $txn_type = 'Debit';
        $remark = $summaryFrom;

        CreateUserWalletLedgerJob::dispatch($fromUser, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());


        $summaryTo = '₹ ' . $amount . ' Fund Received From ' . $fromUser->username . 'Used for Subscription';
        $toWalletTransaction = UserUsdWalletTransaction::create([
            'user_id' => $toUser->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['CREDIT'],
            'amount_in_usd' => $amount,
            'last_amount' => $toUserUsdWallet->balance,
            'summary' => $summaryTo,
            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time' => now()
        ]);
        $toUserUsdWallet->increment('balance', $amount);
        $txn_type = 'Credit';
        $remark = $summaryTo;

        CreateUserWalletLedgerJob::dispatch($toUser, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());

        $summary = UserUsdWalletTransaction::TXN_SUMMARY['ACTIVE PLAN'];
        $walletTransaction = UserUsdWalletTransaction::create([
            'user_id' => $toUser->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd' => $amount,
            'last_amount' => $toUserUsdWallet->balance,
            'summary' => $summary,
            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time' => now()
        ]);

        $toUserUsdWallet->decrement('balance', $amount);
        CreateSubscriptionJob::dispatch($plan, $toUser, $walletTransaction)->delay(now()->addSecond());
        return redirect()->back()->with('notification', ['Package subscribed successfully', 'success']);


    }


    public function showLedger()
    {
        return Inertia::render('Wallet/WalletLedger', []);
    }

    public function getLedger(Request $request)
    {
//        $ledgers = UserWalletLedger::where('user_id', auth()->user()->id)->orderByDesc('id')->simplePaginate(10);
//        return response()->json($ledgers);

        $query = UserWalletLedger::where('user_id', auth()->id())
            ->orderByDesc('id');

        if ($request->filled('type')) {
            $query->where('txn_type', $request->type); // Credit / Debit
        }

        return response()->json(
            $query->simplePaginate(10)
        );
    }

//    public function showInrRequest()
//    {
//        return Inertia::render('Wallet/AddFundRequest');
//    }
//
//    public function getInrRequest()
//    {
//        $InrRequest = UserFundDepositRequest::where('user_id', auth()->user()->id)->where('currency', '=', 'INR')->orderBy('created_at', 'DESC')->simplePaginate(10);
//        return response()->json($InrRequest);
//    }


    public function submitAddFundRequest(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_type' => ['required', 'in:Cash,Cheque,NEFT,IMPS,RTGS,UPI'],
            'txn_id' => [
                'nullable',
                'string',
                'max:255',
                function ($attr, $value, $fail) use ($request) {
                    if ($request->payment_type !== 'Cash' && empty($value)) {
                        $fail('Transaction ID is required for non-cash payments.');
                    }
                },
            ],
            'payment_proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'mimetypes:image/jpeg,image/png,application/pdf',
                'max:2048',
            ],
        ]);

        $user = auth()->user();

        DB::beginTransaction();

        try {
            $filename = time() . '_' . Str::random(12) . '.' .
                $request->file('payment_proof')->extension();

            // 🔐 Store privately (RECOMMENDED)
            $path = $request->file('payment_proof')->storeAs(
                "payment_proofs/{$user->id}",
                $filename,
                'private'
            );

            UserFundAddRequest::create([
                'user_id' => $user->id,
                'payment_type' => $request->payment_type,
                'amount' => $request->amount,
                'txn_id' => $request->txn_id,
                'payment_proof' => $path,
                'status' => 'pending',
            ]);

            DB::commit();

            return back()->with('success', 'Fund request submitted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Cleanup uploaded file if DB failed
            if (!empty($path)) {
                Storage::disk('private')->delete($path);
            }

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

}
