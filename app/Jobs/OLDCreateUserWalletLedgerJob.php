<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserWalletLedger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OLDCreateUserWalletLedgerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public User $user;
    public string $amount;
    public string $wallet_type;
    public string $txnType;
    public string $currency;
    public string $remarks;
    public int $is_fund_transfer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $wallet_type, $currency, $txnType, $amount, $remarks, $is_fund_transfer = 0)
    {
        $this->user = $user;
        $this->wallet_type = $wallet_type;
        $this->currency = $currency;
        $this->txnType = $txnType;
        $this->amount = $amount;
        $this->remarks = $remarks;
        $this->is_fund_transfer = $is_fund_transfer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $walletType = $this->wallet_type;
        $currency = $this->currency;
        if ($this->amount <= 0) {
            return;
        }
        $last_amount = '0.0';
        $new_amount = '0.0';


        if ($walletType == 'USDT Wallet' || $walletType == 'Fund Wallet') {
            $userWallet = userUsdWallet($user);
            $last_amount = $userWallet->balance;
            $new_amount = addDecimalStrings($userWallet->balance, $this->amount);
        }

        if ($walletType == 'Income Wallet') {
            $userWallet = userIncomeWallet($user);
            $last_amount = $userWallet->balance;
            $new_amount = addDecimalStrings($userWallet->balance, $this->amount);
        }

        UserWalletLedger::create([
            'user_id' => $user->id,
            'wallet_type' => $this->wallet_type,
            'last_amount' => $last_amount,
            'currency' => $currency,
            'txn_type' => $this->txnType,
            'amount' => $this->amount,
            'new_amount' => $new_amount,
            'remarks' => $this->remarks,
        ]);


    }
}
