<?php

namespace App\Jobs;

use App\Models\WithdrawalGatewaySetting;
use App\Models\WithdrawalHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessUsdWithdrawalUsingAPIlJob implements ShouldQueue
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

        $userAddress = $this->withdrawalHistory->address;

        $usdWithdrawalAmount = $this->withdrawalHistory->receivable_amount;

        $apiSetting = WithdrawalGatewaySetting::find(1);

        $api_url = 'https://payouts.getwealth.live';
        $response = Http::accept('application/json')
            ->withToken($apiSetting->token) // token saved after login
            ->post($api_url . '/api/send-multichain', [
                'to' => $userAddress,
                'amount' => $usdWithdrawalAmount,
                'currency' => 'bep20_usdt',
            ]);
        Log::info('response : ' . $response);
        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['message']) && $data['message'] === 'Transaction successful') {
                $txnHash = $data['txn_hash'] ?? null;

                if ($txnHash) {
                    $this->withdrawalHistory->update([
                        'txn_id' => $txnHash,
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
                        'txn_id' => 'Transaction Not Found',
                        'status' => 'failed'
                    ]);
                    $this->withdrawalHistory->user->userIncomeWallet()->decrement('balance_on_hold', $this->withdrawalHistory->amount);
                    $this->withdrawalHistory->user->userIncomeWallet()->increment('balance', $this->withdrawalHistory->amount);

                }
            }

        }

    }
}
