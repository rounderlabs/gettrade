<?php

namespace App\Methods;

use App\Jobs\CreateUserWalletLedgerJob;
use App\Models\CryptoPrice;
use App\Models\DepositTransaction;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () {
            $purchase = $this->createPurchase();
            $this->addInrToWallet($purchase);
        });
    }

    private function createPurchase(): Purchase
    {
        $amountUsdt = castDecimalString($this->transaction->amount, 8);

        $cryptoPairPrice = CryptoPrice::where('symbol', 'USDT')
            ->where('pair', 'INR')
            ->first();

        if (!$cryptoPairPrice || bccomp($cryptoPairPrice->price, '0', 8) <= 0) {
            throw new \Exception('Invalid USDT price configuration');
        }

        $rate = castDecimalString($cryptoPairPrice->price, 8);

        $amountInr = multipleDecimalStrings($amountUsdt, $rate, 2);

        return $this->transaction->purchase()->create([
            'user_id' => $this->user->id,
            'deposit_amount' => $amountInr,
            'withdrawal_crypto_price' => $rate,
            'created_at' => $this->transaction->created_at
        ]);
    }

    private function addInrToWallet(Purchase $purchase)
    {
        $wallet = userIncomeWallet($purchase->user);

        $wallet->increment(
            'balance',
            castDecimalString($purchase->deposit_amount, 2)
        );

        $amountUsdt = castDecimalString($this->transaction->amount, 8);
        $rate = $purchase->withdrawal_crypto_price;

        $remark = "Deposit {$amountUsdt} USDT converted at rate ₹{$rate} and credited ₹{$purchase->deposit_amount}";

        CreateUserWalletLedgerJob::dispatch(
            $purchase->user,
            'Income Wallet',
            'INR',
            'Credit',
            $purchase->deposit_amount,
            $remark
        )->delay(now()->addSecond());
    }
}
