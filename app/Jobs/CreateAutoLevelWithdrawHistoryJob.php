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

class CreateAutoLevelWithdrawHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public UserIncomeOnHold $userIncomeOnHold;
    /**
     * Create a new job instance.
     */
    public function __construct(UserIncomeOnHold $userIncomeOnHold)
    {
        $this->userIncomeOnHold = $userIncomeOnHold->withoutRelations();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->userIncomeOnHold->refresh();
        $amount = $this->userIncomeOnHold->level;
        $fees = multipleDecimalStrings($amount, '0.10', 2);
        $user = User::find($this->userIncomeOnHold->user_id);
        $kyc = $user->kyc;

        WithdrawalHistory::create([
            'user_id' => $user->id,
            'bank_name'=>$kyc->bank_name,
            'bank_ifsc'=>$kyc->ifsc_code,
            'bank_account_no'=>$kyc->account_number,
            'upi_id'=>$kyc->upi_id,
            'upi_number'=>$kyc->upi_number,
            'fees' => $fees,
            'amount' => $amount,
            'receivable_amount' => subDecimalStrings($amount, $fees, 8),
            'status' => 'pending',
            'type'=>'level'
        ]);
        $this->userIncomeOnHold->update([
            'level' => 0,
        ]);

    }
}
