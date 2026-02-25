<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserIncomeOnHold;
use App\Models\WithdrawalHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateAutoWorkingWithdrawHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public UserIncomeOnHold $userIncomeOnHold;

    public function __construct(UserIncomeOnHold $userIncomeOnHold)
    {
        $this->onQueue('income');
        $this->userIncomeOnHold = $userIncomeOnHold->withoutRelations();
    }

    public function handle(): void
    {
        DB::transaction(function () {

            /** 🔒 Lock row to avoid double payouts */
            $income = UserIncomeOnHold::lockForUpdate()
                ->findOrFail($this->userIncomeOnHold->id);

            $amount = $income->direct;
            $user = User::findOrFail($income->user_id);
            /** ✅ Nothing to withdraw */
            if (bccomp($amount, '0', 8) <= 0) {
                return;
            }
            $userIncomeWallet = userIncomeWallet($user);
            $userIncomeWallet->increment('balance', $amount);
            $income->update([
                'direct' => 0,
            ]);
            /** 🔻 Reduce total safely if applicable */
            $income->decrement('total', $amount);

//            $fees        = multipleDecimalStrings($amount, '0.10', 8);
//            $receivable  = subDecimalStrings($amount, $fees, 8);
//
//            $user = User::findOrFail($income->user_id);
//            $kyc  = $user->kyc; // optional
//
//            /** 🔁 Idempotency: prevent duplicate same-day working payout */
//            $alreadyExists = WithdrawalHistory::where('user_id', $user->id)
//                ->whereDate('created_at', now()->toDateString())
//                ->where('type', 'working')
//                ->exists();
//
//            if ($alreadyExists) {
//                return;
//            }
//
//            WithdrawalHistory::create([
//                'user_id'           => $user->id,
//
//                // ✅ KYC OPTIONAL
//                'bank_name'         => $kyc->bank_name      ?? null,
//                'bank_ifsc'         => $kyc->ifsc_code      ?? null,
//                'bank_account_no'   => $kyc->account_number ?? null,
//                'upi_id'            => $kyc->upi_id         ?? null,
//                'upi_number'        => $kyc->upi_number     ?? null,
//
//                'fees'              => $fees,
//                'amount'            => $amount,
//                'receivable_amount' => $receivable,
//                'status'            => 'pending',
//                'type'              => 'working',
//            ]);
//
//            /** 🔄 Reset bucket correctly (NO double decrement) */
//            $income->update([
//                'direct' => 0,
//            ]);
//
//            /** 🔻 Reduce total safely if applicable */
//            $income->decrement('total', $amount);
        });
    }
}
