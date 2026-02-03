<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserWalletLedger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateUserWalletLedgerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User   $user,
        public string $wallet_type,   // Fund Wallet | Income Wallet
        public string $currency,      // ALWAYS INR
        public string $txnType,       // Credit | Debit
        public string $amount,        // BASE amount (INR)
        public string $remarks,
        public int    $status = 1
    ) {}

    public function handle()
    {
        if (bccomp($this->amount, '0', 2) <= 0) {
            return;
        }

        /** ---------------------------------
         * Resolve Wallet
         * --------------------------------- */
        if (in_array($this->wallet_type, ['USDT Wallet', 'Fund Wallet'])) {
            $wallet = userUsdWallet($this->user);
        } else {
            $wallet = userIncomeWallet($this->user);
        }

        $lastAmount = $wallet->balance;

        /** ---------------------------------
         * Calculate new balance (CORRECT)
         * --------------------------------- */
        $newAmount = $this->txnType === 'Debit'
            ? subDecimalStrings($lastAmount, $this->amount, 2)
            : addDecimalStrings($lastAmount, $this->amount, 2);

        /** ---------------------------------
         * Store Ledger
         * --------------------------------- */
        UserWalletLedger::create([
            'user_id'     => $this->user->id,
            'wallet_type' => $this->wallet_type,
            'currency'    => $this->currency, // INR
            'txn_type'    => $this->txnType,
            'last_amount' => $lastAmount,
            'amount'      => $this->amount,
            'new_amount'  => $newAmount,
            'remarks'     => $this->remarks,
            'status' => $this->status,
        ]);
    }
}

