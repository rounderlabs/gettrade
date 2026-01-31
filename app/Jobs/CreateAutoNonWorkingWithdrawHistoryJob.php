<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserIncomeOnHold;
use App\Models\WithdrawalHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateAutoNonWorkingWithdrawHistoryJob implements ShouldQueue
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

            $income = UserIncomeOnHold::lockForUpdate()
                ->findOrFail($this->userIncomeOnHold->id);

            $roi = $income->roi;
            $roiOnRoi = $income->roi_on_roi;
            $rank = $income->rank;

            $amount = addDecimalStrings(
                addDecimalStrings($roi, $roiOnRoi, 8),
                $rank,
                8
            );

            // ✅ SAFETY: nothing to withdraw
            if (bccomp($amount, '0', 8) <= 0) {
                return;
            }

            $fees = multipleDecimalStrings($amount, '0.10', 8);
            $receivable = subDecimalStrings($amount, $fees, 8);

            $user = User::findOrFail($income->user_id);
            $kyc = $user->kyc;

            // ✅ SAFETY: KYC missing
//            if (!$kyc) {
//                throw new ModelNotFoundException('KYC not found for user ' . $user->id);
//            }

            // ✅ IDEMPOTENCY CHECK (same day)
            $alreadyExists = WithdrawalHistory::where('user_id', $user->id)
                ->whereDate('created_at', now()->toDateString())
                ->where('type', 'non_working')
                ->exists();

            if ($alreadyExists) {
                return;
            }

            WithdrawalHistory::create([
                'user_id' => $user->id,
                'bank_name' => $kyc->bank_name ?? null,
                'bank_ifsc' => $kyc->ifsc_code ?? null,
                'bank_account_no' => $kyc->account_number ?? null,
                'upi_id' => $kyc->upi_id ?? null,
                'upi_number' => $kyc->upi_number ?? null,
                'fees' => $fees,
                'amount' => $amount,
                'receivable_amount' => $receivable,
                'status' => 'pending',
                'type' => 'non_working', // ✅ FIXED
            ]);

            // ✅ Reset buckets
            $income->update([
                'roi' => 0,
                'roi_on_roi' => 0,
                'rank' => 0,
            ]);

            // ✅ Decrement total safely
            $income->decrement('total', $amount);
        });
    }
}
