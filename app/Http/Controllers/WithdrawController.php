<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WithdrawController extends Controller
{

    public function withdrawRequestForm()
    {
        $userIncomeWallet = userIncomeWallet(auth()->user());
        return Inertia::render('Withdraw/WithdrawRequest', [
            'available_balance'=>$userIncomeWallet->balance,
        ]);
    }



    public function submitWithdrawRequestForm(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'amount' => ['required', 'numeric', 'gte:10',
                function ($attribute, $value, $fail) {
                    if (is_null(auth()->user()->userIncomeWallet) || userIncomeWallet(\auth()->user())->balance < $value) {
                        $fail('You dont have sufficient balance');
                    }
                    if (castDecimalString($value, 2) < '10.00') {
                        $fail('Minimum amount should be 10 ');
                    }

                    if (WithdrawalHistory::where('user_id', auth()->user()->id)->whereDate('created_at', now()->format('Y-m-d'))->exists()) {
                        $fail('Only one withdrawal is allowed per day.');
                    }

                    if (userStop(\auth()->user())->withdrawal) {
                        $fail('Unable to process request at this time');
                    }
                },

            ],

        ]);
        $userIncomeWallet = userIncomeWallet($user);

        $withdrawal_amount = $userIncomeWallet->balance;
        $kyc = $user->kyc;
        if ($kyc->status == 'approved'){
            return redirect()->route('kyc.index')->with('notification', ['Update Your KYC, One Approve Then Only You Can Request Withdraw', 'danger']);
        }

        if ($withdrawal_amount < $request->amount){
            return redirect()->back()->with('notification', ['You dont have sufficient balance', 'danger']);
        }

        $withdrawAmount = castDecimalString($request->amount, 2);
        $fees = multipleDecimalStrings($withdrawAmount, '0.10', 2);

        $userIncomeWallet = $user->userIncomeWallet()->select('id', 'balance', 'balance_on_hold')->first();
        $userIncomeWallet->decrement('balance', $withdrawAmount);
        $userIncomeWallet->increment('balance_on_hold', $withdrawAmount);

        WithdrawalHistory::create([
            'user_id' => $user->id,
            'bank_name'=>$kyc->bank_name,
            'bank_ifsc'=>$kyc->ifsc_code,
            'bank_account_no'=>$kyc->account_number,
            'upi_id'=>$kyc->upi_id,
            'upi_number'=>$kyc->upi_number,
            'fees' => $fees,
            'amount' => $withdrawAmount,
            'receivable_amount' => subDecimalStrings($withdrawAmount, $fees, 8),
            'status' => 'pending'
        ]);
        return redirect()->route('history.withdrawal')->with('notification', ['Withdrawal submitted successfully', 'success']);
    }

}
