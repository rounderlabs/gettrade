<?php

namespace App\Http\Controllers;


use App\Jobs\CreateSubscriptionJob;
use App\Jobs\CreateUserWalletLedgerJob;
use App\Jobs\CreateZeroPinSubscriptionJob;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserUsdWallet;
use App\Models\UserUsdWalletAdminUpdate;
use App\Models\UserUsdWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserUsdWalletAdminUpdateController extends Controller
{
    public function updateUserWallet(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', Rule::in(['debit', 'credit'])],
            'amount' => ['required', 'gt:0'],
        ]);

        $user = User::find($request->user_id);
        $userUsdWallet = userUsdWallet($user);

        if ($request->type == 'debit' && castDecimalString($userUsdWallet->balance, 4) < castDecimalString($request->amount, 4)) {
            return redirect()->back()->with('notification', ['User wallet balance is less than deduction amount.', 'danger']);
        }
        $previousAmount = $userUsdWallet->balance;
        if ($request->type == 'debit') {
            $userUsdWallet->decrement('balance', castDecimalString($request->amount, 2));
        }
        if ($request->type == 'credit') {
            $userUsdWallet->increment('balance', castDecimalString($request->amount, 2));
        }
        UserUsdWalletAdminUpdate::create([
            'user_id' => $user->id,
            'amount' => castDecimalString($request->amount, 4),
            'previous_amount' => $previousAmount,
            'type' => $request->type
        ]);
        return redirect()->route('admin.user.create', [$user->id])->with('notification', ['User Wallet Updated Successfully', 'success']);
    }

    public function updateUserActivationWallet(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', Rule::in(['debit', 'credit'])],
            'amount' => ['required', 'gt:0'],
        ]);

        $user = User::find($request->user_id);
        $userUsdWallet = userUsdWallet($user);

        if ($request->type == 'debit' && castDecimalString($userUsdWallet->balance, 4) < castDecimalString($request->amount, 4)) {
            return redirect()->back()->with('notification', ['User wallet balance is less than deduction amount.', 'danger']);
        }
        $previousAmount = $userUsdWallet->balance;
        if ($request->type == 'debit') {
            $txn_type = 'Debit';
            $userUsdWallet->decrement('balance', castDecimalString($request->amount, 2));
        }
        if ($request->type == 'credit') {
            $txn_type = 'Credit';
            $userUsdWallet->increment('balance', castDecimalString($request->amount, 2));
        }
        UserUsdWalletAdminUpdate::create([
            'user_id' => $user->id,
            'amount' => castDecimalString($request->amount, 4),
            'previous_amount' => $previousAmount,
            'type' => $request->type
        ]);

        $amount = $request->amount;
        $walletType = 'USDT Wallet';
        $currency = 'USDT';
        $remark = 'Amount $' . $request->amount . ' has been ' . $txn_type . 'ed ';
        CreateUserWalletLedgerJob::dispatch($user, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());


        return redirect()->back()->with('notification', ['User Wallet Updated Successfully', 'success']);
    }

    public function updateUserIncomeWallet(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', Rule::in(['debit', 'credit'])],
            'amount' => ['required', 'gt:0'],
        ]);

        $user = User::find($request->user_id);
        $userIncomeWallet = userIncomeWallet($user);

        if ($request->type == 'debit' && castDecimalString($userIncomeWallet->balance, 4) < castDecimalString($request->amount, 4)) {
            return redirect()->back()->with('notification', ['User wallet balance is less than deduction amount.', 'danger']);
        }
        $previousAmount = $userIncomeWallet->balance;
        if ($request->type == 'debit') {
            $userIncomeWallet->decrement('balance', castDecimalString($request->amount, 2));
        }
        if ($request->type == 'credit') {
            $userIncomeWallet->increment('balance', castDecimalString($request->amount, 2));
        }
        UserUsdWalletAdminUpdate::create([
            'user_id' => $user->id,
            'amount' => castDecimalString($request->amount, 4),
            'previous_amount' => $previousAmount,
            'type' => $request->type
        ]);
        return redirect()->route('admin.user.create', [$user->id])->with('notification', ['User Wallet Updated Successfully', 'success']);
    }


    public function createUserInvestment(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', Rule::in(['debit', 'credit'])],
            'investment_type' => ['required', Rule::in(['paid', 'zero_pin'])],
            'amount' => ['required', 'gte:50'],

        ]);

        $user = User::find($request->user_id);
        $amount = castDecimalString($request->amount, 2);
        $userID = auth()->user()->id;
        $plan = Plan::where('id', '=', 1)->first();
        $investmentType = $request->investment_type;

        $isMultipleOfHundred = $this->isMultipleOfFifty($amount);
        if (!$isMultipleOfHundred) {
            return redirect()->back()->with('notification', ['Amount Must BE Multiple Of 50', 'danger']);
        }


        if ($amount < $plan->start_amount || $amount > $plan->end_amount) {
            DB::rollBack();
            return redirect()->back()->with('notification', ['Entered Amount is not in Selected Plan Range', 'danger']);
        }

        $userUsdWallet = userUsdWallet($user);


        $previousAmount = $userUsdWallet->balance;

        $txn_type = 'credit';
        $userUsdWallet->increment('balance', castDecimalString($request->amount, 2));

        UserUsdWalletAdminUpdate::create([
            'user_id' => $user->id,
            'amount' => castDecimalString($request->amount, 4),
            'previous_amount' => $previousAmount,
            'type' => $request->type
        ]);


        $walletType = 'USDT Wallet';
        $currency = 'USDT';
        $remark = 'Amount $' . $request->amount . ' has been ' . $txn_type . 'ed ';
        CreateUserWalletLedgerJob::dispatch($user, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());



        $userUsdWallet = userUsdWallet($user);
        if (is_null($userUsdWallet)) {

            Log::info('wallet Not Found');
            return redirect()->back()->with('notification', ['Insufficient Balance', 'danger']);
        }

        $userUsdWallet->refresh();

        if ($userUsdWallet->balance < $amount) {

            Log::info('wallet balance Low');
            return redirect()->back()->with('notification', ['Insufficient Balance', 'danger']);
        }

        $walletTransaction = UserUsdWalletTransaction::create([
            'user_id' => $request->user()->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd' => $amount,
            'last_amount' => $userUsdWallet->balance,
            'summary' => 'Investment',
            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time' => now()
        ]);

        $userUsdWallet->decrement('balance', $amount);

        if (is_null($walletTransaction)) {
            return redirect()->back()->with('notification', ['Please try again later', 'danger']);
        }


        if ($investmentType == 'paid') {
            CreateSubscriptionJob::dispatch($plan, $user, $walletTransaction)->delay(now()->addSecond());
        } elseif ($investmentType == 'zero_pin') {
            CreateZeroPinSubscriptionJob::dispatch($plan, $user, $walletTransaction)->delay(now()->addSecond());
        }

        return redirect()->back()->with('notification', ['Investment added successfully', 'success']);


    }

    function isMultipleOfFifty($number){
        $result = $number % 50;
        if ($result == 0 ){
            return true;
        }else{
            return false;
        }

    }

}
