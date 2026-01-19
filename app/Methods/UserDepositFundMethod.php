<?php

namespace App\Methods;

use App\Jobs\CreateUserWalletLedgerJob;
use App\Models\DepositTransaction;
use App\Models\Purchase;
use App\Models\User;

class UserDepositFundMethod
{
    private DepositTransaction $transaction;
    private User $user;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
        $this->user = $this->transaction->user;
    }

    public static function fromTransaction(DepositTransaction $transaction): self
    {
        return new static($transaction);
    }

    public function init()
    {
        $purchase = $this->createPurchase();

        $this->addCoinToWallet($purchase);
    }

    private function createPurchase(): Purchase
    {
        return $this->transaction->purchase()->create([
            'user_id' => $this->user->id,
            'deposit_amount' => castDecimalString($this->transaction->amount, 2),
            'created_at' => $this->transaction->created_at
        ]);
    }

    private function addCoinToWallet(Purchase $purchase)
    {
        $userUsdWallet = userUsdWallet($purchase->user);
        $userUsdWallet->increment('balance', castDecimalString($purchase->deposit_amount, 2));

        $amount = $purchase->deposit_amount;
        $walletType = 'USDT Wallet';
        $currency = 'USDT';
        $txn_type = 'Credit';
        $remark = 'Amount $ '.$amount.' has been added to Activation Wallet successfully';
        CreateUserWalletLedgerJob::dispatch($purchase->user, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());
    }
}
