<?php

namespace App\Blockchains\Bitcoin;


use App\Models\CoinType;

class BitcoinHandler
{

    public static function init(): self
    {
        return new static();
    }

    public function listWallets()
    {
        return bitcoind()->listwallets()->get();
    }

    public function getBestBlockHash()
    {
        return bitcoind()->getBestBlockHash()->get();
    }

    public function createWallet(string $walletName = 'wallet', bool $disablePrivateKeys = false)
    {
        return bitcoind()->createwallet($walletName, $disablePrivateKeys);
    }

    public function listWalletDir()
    {
        return bitcoind()->listwalletdir()->get();
    }

    public function getwalletinfo()
    {
        return bitcoind()->getwalletinfo()->get();
    }

    public function getNewAddress(string $label = '', string $addressType = CoinType::TYPES['legacy'])
    {
        $address = bitcoind()->getnewaddress($label, $addressType)->get();
        $privateKey = $this->dumpprivkey($address);
        return [
            'address' => $address,
            'priv_key' => $privateKey
        ];
    }

    public function dumpprivkey(string $address)
    {
        return bitcoind()->dumpprivkey($address)->get();
    }

    public function getTrasnsaction(string $txnId)
    {
        return bitcoind()->gettransaction($txnId)->get();
    }

    public function sendToAddress(string $address, string $amount, bool $subtractFromFee = true, string $comment = '', string $commentTo = '')
    {
        $getBalance = $this->getBalance();
        if ($getBalance < $amount) {
            return null;
        } else {
            return bitcoind()->sendtoaddress($address, $amount, $comment, $commentTo, $subtractFromFee);
        }
    }

    public function getBalance()
    {
        return bitcoind()->getbalance()->get();
    }

}
