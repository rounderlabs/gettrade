<?php

namespace App\Jobs;

use App\Blockchains\Tron\Handler;
use App\Models\AdminWithdrawProcessWallet;
use App\Models\WithdrawalHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessWithdrawalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public WithdrawalHistory $withdrawalHistory;


    /**
     * Create a new job instance.
     *
     * @param WithdrawalHistory $withdrawalHistory
     */
    public function __construct(WithdrawalHistory $withdrawalHistory)
    {
        $this->withdrawalHistory = $withdrawalHistory->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if ($this->withdrawalHistory->status != 'pending') {
            return;
        }

        $this->withdrawalHistory->update([
            'status' => 'processing'
        ]);

        $withdrawCoin = $this->withdrawalHistory->withdrawCoin;
        if ($withdrawCoin->name == 'bep20_usdt') {
            $adminProcessWallet = AdminWithdrawProcessWallet::where('status', 'active')->where('name', 'bep20_usdt')->whereNotNull('address')->whereNotNull('priv_key')->first();
        } else {
            $adminProcessWallet = null;
        }

        if (is_null($adminProcessWallet)) {
            $this->fail();
            return;
        }

        $userAddress = $this->withdrawalHistory->address;

        $withdrawalableAmount = $this->withdrawalHistory->receivable_amount;


        $transaction = Handler::init()->sendUsdt($adminProcessWallet->address, $adminProcessWallet->priv_key, $userAddress, floatval($withdrawalableAmount));

        if ($transaction && is_array($transaction)) {
            if (isset($transaction['result']) && $transaction['result'] == true) {
                $this->withdrawalHistory->update([
                    'txn_id' => $transaction['txid'],
                    'status' => 'success'
                ]);
                $this->withdrawalHistory->user->userIncomeWallet()->decrement('balance_on_hold', $this->withdrawalHistory->amount);
            } else {
                $this->withdrawalHistory->update([
                    'status' => 'success'
                ]);

                $this->withdrawalHistory->user->userIncomeWallet()->decrement('balance_on_hold', $this->withdrawalHistory->amount);
            }


        }


    }

    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     *
     * @return void
     */
    public function failed(Throwable $exception)
    {
        //        $this->withdrawalHistory->update([
        //            'status' => 'failed'
        //        ]);
    }


}
