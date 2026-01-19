<?php


namespace App\Blockchains\Tron;

use Carbon\Carbon;
use IEXBase\TronAPI\Tron;

class TransferContractTransaction
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

    public function getAmount(bool $fromTron = true)
    {
        $getParamentValue = $this->getParameterValue();
        if ($fromTron) {
            $tron = new Tron();
            return $tron->fromTron($getParamentValue['amount']);
        } else {
            return $getParamentValue['amount'];
        }
    }

    public function getParameterValue(): array
    {
        return $this->txn['raw_data']['contract'][0]['parameter']['value'];
    }

    public function getFromAddress(bool $hex = false)
    {
        $getParamentValue = $this->getParameterValue();
        if (!$hex) {
            $tron = new Tron();
            return $tron->fromHex($getParamentValue['owner_address']);
        } else {
            return $getParamentValue['amount'];
        }
    }

    public function getToAddress(bool $hex = false)
    {
        $getParamentValue = $this->getParameterValue();
        if (!$hex) {
            $tron = new Tron();
            return $tron->fromHex($getParamentValue['to_address']);
        } else {
            return $getParamentValue['amount'];
        }
    }

    public function getTxnId()
    {
        return $this->txn['txID'];
    }

    public function getTxnDateTime(string $format = 'Y-m-d H:i:s')
    {
        return Carbon::createFromTimestampMs($this->getBlockTimestamp())->format($format);
    }

    public function getBlockTimestamp(): string
    {
        return (string)$this->txn['block_timestamp'];
    }

    public function getTxnTimeStamp(): string
    {
        return $this->txn['raw_data']['timestamp'];
    }

    public function getBlockNumber()
    {
        return $this->txn['blockNumber'];
    }
}
