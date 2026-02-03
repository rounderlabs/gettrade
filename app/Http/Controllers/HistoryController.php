<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class HistoryController extends Controller
{
    public function showDepositHistory()
    {
        $depositTransactions = auth()->user()->depositTransactions()->with('depositTransactionable')->orderByDesc('id')->get();
        return Inertia::render('History/DepositHistory', [
            'history' => $depositTransactions

        ]);
    }

//    public function showWithdrawalHistory()
//    {
//        $userIncomeOnHold = auth()->user()->userIncomeOnHold;
//        $withdrawalTransactions = auth()->user()->withdrawalHistories()->orderByDesc('id')->get();
//        return Inertia::render('History/WithdrawalHistory', [
//            'history' => $withdrawalTransactions,
//            'income_on_hold' => $userIncomeOnHold
//        ]);
//    }


    public function showWithdrawalHistory()
    {
        $user = auth()->user();

        $incomeOnHold = $user->userIncomeOnHold;

        $withdrawals = $user->withdrawalHistories()
            ->orderByDesc('id')
            ->get()
            ->map(function ($txn) use ($user) {

                return [
                    'id'                   => $txn->id,
                    'status'               => $txn->status,
                    'created_at'           => $txn->created_at->format('d M Y'),

                    // BASE values (INR)
                    'amount_base'          => $txn->amount,
                    'fees_base'            => $txn->fees,
                    'receivable_base'      => $txn->receivable_amount,

                    // DISPLAY values (user currency)
                    'amount_display'       => displayAmount($txn->amount, $user),
                    'fees_display'         => displayAmount($txn->fees, $user),
                    'receivable_display'   => displayAmount($txn->receivable_amount, $user),
                ];
            });

        return Inertia::render('History/WithdrawalHistory', [
            'history' => $withdrawals,

            'income_on_hold' => [
                'direct_display' => displayAmount($incomeOnHold->direct ?? '0.00', $user),

                'return_display' => displayAmount(
                    addDecimalStrings(
                        $incomeOnHold->roi ?? '0.00',
                        addDecimalStrings(
                            $incomeOnHold->roi_on_roi ?? '0.00',
                            $incomeOnHold->rank ?? '0.00'
                        )
                    ),
                    $user
                ),
            ],
        ]);
    }



    public function showWalletTxnHistory()
    {
        return Inertia::render('History/WalletTxnHistory');
    }

    public function showTokenTxnHistory()
    {
        return Inertia::render('Token/TokenWallet');
    }

    public function getTokenTxnHistory()
    {
        $history = auth()->user()->userUsdWalletTransactions()->withCasts(['created_at' => 'datetime:Y-m-d'])->orderByDesc('id')->paginate(10);
        return response()->json($history);
    }

    public function getWalletTxnHistory()
    {
        $history = auth()->user()->userUsdWalletTransactions()->withCasts(['created_at' => 'datetime:Y-m-d'])->orderByDesc('id')->paginate(10);
        return response()->json($history);
    }
}
