<?php

namespace App\Jobs;

use App\Blockchains\Bep20Usd\BEP20;
use App\Blockchains\Bep20Usd\BscscanApi;
use App\Models\AdminWithdrawProcessWallet;
use App\Models\WithdrawalHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessUsdWithdrawalJob implements ShouldQueue
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
        $this->onQueue('default');
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

        $adminProcessWallet = AdminWithdrawProcessWallet::where('status', 'active')->where('name', 'bep20_usdt')->whereNotNull('address')->whereNotNull('priv_key')->first();

        if (is_null($adminProcessWallet)) {
            $this->fail();
            return;
        }

        $userAddress = $this->withdrawalHistory->address;

        $usdWithdrawalAmount = $this->withdrawalHistory->receivable_amount;

        // Initiate Transaction From Here Now
        $uri = 'https://bsc-dataseed1.defibit.io/';

        $apiKey = 'QVG2GK41ASNSD21KJTXUAQ4JTRQ4XUQZCX';

        $api = new BscscanApi($apiKey);
        $config = [
            'contract_address' => '0x55d398326f99059fF775485246999027B3197955',
            'decimals' => 18,
        ];
        $bep20 = new BEP20($api, $config);
        $transaction = $bep20->transfer($adminProcessWallet->priv_key, $userAddress, floatval($usdWithdrawalAmount));
        Log::info($transaction);
        if ($transaction) {
            $this->withdrawalHistory->update([
                'txn_id' => $transaction,
                'status' => 'success'
            ]);
            $this->withdrawalHistory->user->userIncomeWallet()->decrement('balance_on_hold', $this->withdrawalHistory->amount);
            $amount = $this->withdrawalHistory->amount;
            $walletType = 'Income Wallet';
            $currency = 'USDT';
            $txn_type = 'Debit';
            $remark = 'Withdrawals of $' . $amount . ' has been processed successfully';
            CreateUserWalletLedgerJob::dispatch($this->withdrawalHistory->user, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());
        } else {
            $this->withdrawalHistory->update([
                'txn_id' => 'Trasaction Not Found',
                'status' => 'failed'
            ]);
            $this->withdrawalHistory->user->userIncomeWallet()->decrement('balance_on_hold', $this->withdrawalHistory->amount);
            $this->withdrawalHistory->user->userIncomeWallet()->increment('balance', $this->withdrawalHistory->amount);

        }
    }
}
