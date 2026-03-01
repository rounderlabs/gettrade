<?php

namespace App\Http\Controllers;

use App\Jobs\CreateSubscriptionJob;
use App\Jobs\CreateUserWalletLedgerJob;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserFundAddRequest;
use App\Models\UserUsdWalletTransaction;
use App\Models\UserWalletLedger;
use App\Services\AdminNotificationService;
use App\Services\CurrencyService;
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

        $baseCurrency    = 'INR';
        $displayCurrency = $user->preferred_currency ?? 'INR';

        $investmentWallet = userUsdWallet($user);
        $incomeWallet     = userIncomeWallet($user);

        $depositHistory = UserFundAddRequest::where('user_id', $user->id)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($row) use ($baseCurrency, $displayCurrency) {
                return [
                    'amount_base'    => $row->amount,
                    'amount_display' => CurrencyService::convert(
                        (string) $row->amount,
                        $baseCurrency,
                        $displayCurrency
                    ),
                    'payment_type' => $row->payment_type,
                    'status'       => $row->status,
                    'created_at'   => $row->created_at->format('d M Y H:i'),
                ];
            });

        return Inertia::render('Wallet/Index', [
            'user_usd_wallet' => [
                'balance_base'    => $investmentWallet->balance,
                'balance_display' => CurrencyService::convert(
                    (string) $investmentWallet->balance,
                    $baseCurrency,
                    $displayCurrency
                ),
            ],

            'user_income_wallet' => [
                'balance_base'    => $incomeWallet->balance,
                'balance_display' => CurrencyService::convert(
                    (string) $incomeWallet->balance,
                    $baseCurrency,
                    $displayCurrency
                ),
            ],

            'deposit_histories' => $depositHistory,
            'display_currency'  => $displayCurrency,
            'permissions' => [
                'can_transfer' => $user->can_transfer,
                'can_activate_downline' => $user->can_activate_downline,
            ]
        ]);
    }


    public function showFundTransferForm()
    {
        $user = auth()->user();

        if (!$user->canTransfer()) {
            abort(403, 'Transfer permission denied.');
        }

        $baseCurrency = 'INR';
        $displayCurrency = $user->preferred_currency ?? 'INR';

        $wallet = userUsdWallet($user); // stored in INR

        return Inertia::render('Wallet/FundTransferFrom', [
            'user_usd_wallet' => [
                'balance_base' => $wallet->balance, // INR
                'balance_display' => CurrencyService::convert(
                    (string) $wallet->balance,
                    $baseCurrency,
                    $displayCurrency
                ),
            ],
            'display_currency' => $displayCurrency,
        ]);
    }


//    public function submitFundTransferFrom(Request $request)
//    {
//        $request->validate([
//            'amount' => ['required', 'numeric', 'gte:20'],
//            'username' => ['required', 'string', 'exists:users,username',]
//        ]);
//        $fromUser = auth()->user();
//        $toUser = User::where('username', $request->username)->first();
//        $fromUserUsdWallet = userUsdWallet($fromUser);
//        $toUserUsdWallet = userUsdWallet($toUser);
//        if ($request->amount > $fromUserUsdWallet->balance) {
//            return redirect()->back()->with('notification', ['Insufficient Fund.', 'danger']);
//        }
//        $fromUserUsdWallet->decrement('balance', $request->amount);
//        $summaryFrom = '₹ ' . $request->amount . ' Fund Transferred To ' . $toUser->username;
//        $fromWalletTransaction = UserUsdWalletTransaction::create([
//            'user_id' => $fromUser->id,
//            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
//            'amount_in_usd' => $request->amount,
//            'last_amount' => $toUserUsdWallet->balance,
//            'summary' => $summaryFrom,
//            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
//            'txn_time' => now()
//        ]);
//        $amount = $request->amount;
//        $walletType = 'USDT Wallet';
//        $currency = 'INR';
//        $txn_type = 'Debit';
//        $remark = $summaryFrom;
//
//        CreateUserWalletLedgerJob::dispatch($fromUser, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());
//
//
//        $summaryTo = '₹ ' . $request->amount . ' Fund Received From ' . $fromUser->username;
//        $toWalletTransaction = UserUsdWalletTransaction::create([
//            'user_id' => $request->user()->id,
//            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['CREDIT'],
//            'amount_in_usd' => $request->amount,
//            'last_amount' => $toUserUsdWallet->balance,
//            'summary' => $summaryTo,
//            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
//            'txn_time' => now()
//        ]);
//        $toUserUsdWallet->increment('balance', $request->amount);
//        $txn_type = 'Credit';
//        $remark = $summaryTo;
//
//        CreateUserWalletLedgerJob::dispatch($fromUser, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());
//
//
//        return redirect()->back()->with('notification', ['Funds Transferred Successfully.', 'success']);
//    }

    public function submitFundTransferFrom(Request $request)
    {
        $request->validate([
            'amount'   => ['required', 'numeric', 'gt:0'],
            'username' => ['required', 'exists:users,username'],
        ]);

        $user = auth()->user();

        if (!$user->canTransfer()) {
            abort(403, 'Transfer permission denied.');
        }

        $fromUser = auth()->user();
        $toUser   = User::where('username', $request->username)->firstOrFail();

        /**
         * ------------------------------------
         * STEP 1: Resolve BASE amount (INR)
         * ------------------------------------
         */
        $displayCurrency = $fromUser->preferred_currency ?? 'INR';

        if ($displayCurrency === 'INR') {
            $amountInInr = castDecimalString($request->amount, 2);
        } else {
            $amountInInr = CurrencyService::convert(
                (string) $request->amount,
                $displayCurrency,
                'INR'
            );
        }

        /**
         * ------------------------------------
         * STEP 2: Wallet (stored in INR)
         * ------------------------------------
         */
        $fromWallet = userUsdWallet($fromUser); // your wallet table (INR based)

        if (bccomp($fromWallet->balance, $amountInInr, 2) < 0) {
            return back()->with('notification', [
                'Insufficient balance',
                'danger'
            ]);
        }

        DB::beginTransaction();

        /**
         * ------------------------------------
         * STEP 3: Debit sender
         * ------------------------------------
         */
        $fromWallet->decrement('balance', $amountInInr);

        UserUsdWalletTransaction::create([
            'user_id'          => $fromUser->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd'    => $amountInInr, // ⚠️ column name legacy, value INR
            'last_amount'      => $fromWallet->balance + $amountInInr,
            'summary'          => 'Fund Transfer',
            'status'           => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time'         => now(),
        ]);

        /**
         * ------------------------------------
         * STEP 4: Credit receiver
         * ------------------------------------
         */
        $toWallet = userUsdWallet($toUser);
        $toWallet->increment('balance', $amountInInr);

        UserUsdWalletTransaction::create([
            'user_id'          => $toUser->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['CREDIT'],
            'amount_in_usd'    => $amountInInr,
            'last_amount'      => $toWallet->balance - $amountInInr,
            'summary'          => 'Fund Received',
            'status'           => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time'         => now(),
        ]);

        /**
         * ------------------------------------
         * STEP 5: Ledger (always INR)
         * ------------------------------------
         */
        CreateUserWalletLedgerJob::dispatch(
            $fromUser,
            'Fund Wallet',
            'INR',            // ✅ FIXED, no variable needed
            'Debit',
            $amountInInr,
            'Fund Transfer'
        );

        CreateUserWalletLedgerJob::dispatch(
            $toUser,
            'Fund Wallet',
            'INR',
            'Credit',
            $amountInInr,
            'Fund Received'
        );

        DB::commit();
        AdminNotificationService::notify(
            'transfer',
            "🔁 <b>Fund Transfer</b>\nFrom: {$fromUser->username}\nTo: {$toUser->username}\nAmount: {$amountInInr}"
        );
        return back()->with('notification', [
            'Funds transferred successfully',
            'success'
        ]);
    }



    public function showActivateMemberForm()
    {
        $user = auth()->user();
        if (!$user->canActivateDownline()) {
            abort(403, 'Activation permission denied.');
        }

        $baseCurrency = 'INR';
        $displayCurrency = $user->preferred_currency ?? 'INR';

        $plans = Plan::where('is_active', 1)
            ->orderBy('id', 'ASC')
            ->get()
            ->map(function ($plan) use ($baseCurrency, $displayCurrency) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'amount_base' => $plan->amount, // INR
                    'amount_display' => CurrencyService::convert(
                        (string) $plan->amount,
                        $baseCurrency,
                        $displayCurrency
                    ),
                ];
            });

        return Inertia::render('Wallet/ActivateMemberFrom', [
            'plans' => $plans,
            'display_currency' => $displayCurrency,
        ]);
    }


    public function submitActivationForm(Request $request)
    {
        $request->validate([
            'username' => ['required', 'exists:users,username'],
            'plan_id'  => ['required', 'exists:plans,id'],
        ]);

        $user   = auth()->user();
        if (!$user->canActivateDownline()) {
            abort(403, 'Activation permission denied.');
        }
        $toUser = User::where('username', $request->username)->firstOrFail();
        $plan   = Plan::findOrFail($request->plan_id);

        $baseCurrency = 'INR';

        /**
         * -----------------------------------
         * BASE AMOUNT (INR ONLY)
         * -----------------------------------
         */
        $amountBase = castDecimalString($plan->amount, 2);

        $fromWallet = userUsdWallet($user);
        $toWallet   = userUsdWallet($toUser);

        if (bccomp($fromWallet->balance, $amountBase, 2) < 0) {
            return back()->withErrors([
                'amount' => 'Insufficient wallet balance'
            ]);
        }

        DB::beginTransaction();

        // 🔴 Debit sender
        $fromWallet->decrement('balance', $amountBase);

        UserUsdWalletTransaction::create([
            'user_id'          => $user->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd'    => $amountBase, // BASE (INR)
            'last_amount'      => $fromWallet->balance,
            'summary'          => 'Team member activation. User : '.'$toUser',
            'status'           => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time'         => now(),
        ]);

        CreateUserWalletLedgerJob::dispatch(
            $user,
            'Fund Wallet',
            $baseCurrency,
            'Debit',
            $amountBase,
            'Team member activation'
        );

        // 🟢 Credit receiver
        $toWallet->increment('balance', $amountBase);

        UserUsdWalletTransaction::create([
            'user_id'          => $toUser->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['CREDIT'],
            'amount_in_usd'    => $amountBase,
            'last_amount'      => $toWallet->balance,
            'summary'          => 'Activation fund received',
            'status'           => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time'         => now(),
        ]);

        CreateUserWalletLedgerJob::dispatch(
            $toUser,
            'Fund Wallet',
            $baseCurrency,
            'Credit',
            $amountBase,
            'Activation fund received'
        );

        // 🔴 Activate subscription
        $toWallet->decrement('balance', $amountBase);

        $subscriptionTxn = UserUsdWalletTransaction::create([
            'user_id'          => $toUser->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd'    => $amountBase,
            'last_amount'      => $toWallet->balance,
            'summary'          => UserUsdWalletTransaction::TXN_SUMMARY['ACTIVE PLAN'],
            'status'           => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time'         => now(),
        ]);

        CreateSubscriptionJob::dispatch($plan, $toUser, $subscriptionTxn);

        DB::commit();

        return back()->with('notification', [
            'Package subscribed successfully',
            'success'
        ]);
    }


    public function showLedger()
    {
        return Inertia::render('Wallet/WalletLedger', []);
    }


    public function getLedger(Request $request)
    {
        $user = auth()->user();
        $baseCurrency = 'INR';
        $displayCurrency = $user->preferred_currency ?? 'INR';

        $query = UserWalletLedger::where('user_id', $user->id)
            ->orderByDesc('id');

        if ($request->filled('type')) {
            $query->where('txn_type', $request->type); // Credit / Debit
        }

        $ledgers = $query->simplePaginate(10);

        // 🔁 Append display amount
        $ledgers->getCollection()->transform(function ($ledger) use ($baseCurrency, $displayCurrency) {

            $ledger->amount_base = $ledger->amount; // INR stored
            $ledger->amount_display = $baseCurrency === $displayCurrency
                ? $ledger->amount
                : CurrencyService::convert(
                    (string) $ledger->amount,
                    $baseCurrency,
                    $displayCurrency
                );

            return $ledger;
        });

        return response()->json($ledgers);
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
