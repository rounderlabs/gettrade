<?php


namespace App\Blockchains\Bitcoin;


class Transaction
{
    private array $txn;

    public function __construct(array $txn)
    {
        $this->txn = $txn;
    }

    public static function init(array $txn): self
    {
        return new static($txn);
    }

    public function getReceived()
    {
        $res = [];
        foreach ($this->txn['details'] as $detail) {
            if ($detail['category'] == 'receive') {
                $receive = [
                    'txn_id' => $this->txn['txid'],
                    'address' => $detail['address'],
                    'category' => 'receive',
                    'amount' => $detail['amount'],
                    'label' => $detail['label'],
                    'vout' => $detail['vout'],
                    'confirmations' => $this->txn['confirmations'],
                    'txn_time' => $this->txn['timereceived']
                ];
                array_push($res, $receive);
            }
        }
        return $res;
    }
}
