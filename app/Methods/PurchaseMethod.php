<?php


namespace App\Methods;


use App\Jobs\CreateSubscriptionJob;
use App\Models\DepositTransaction;
use App\Models\Purchase;
use App\Models\User;

class PurchaseMethod
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
        CreateSubscriptionJob::dispatch($purchase, $this->transaction, $this->user);

    }

    private function createPurchase(): Purchase
    {
        $tokenPrice = tokenPrice();
        $tokenQty = divDecimalStrings($this->transaction->amount_in_usd, $tokenPrice, 8);

        return $this->transaction->purchase()->create([
            'user_id' => $this->user->id,
            'crypto' => $this->transaction->crypto,
            'crypto_price' => $this->transaction->crypto_price,
            'deposit_amount' => $this->transaction->amount,
            'deposit_amount_in_usd' => $this->transaction->amount_in_usd,
            'token_price' => castDecimalString($tokenPrice, 4),
            'token_qty' => $tokenQty,
            'created_at' => $this->transaction->created_at
        ]);
    }


}
