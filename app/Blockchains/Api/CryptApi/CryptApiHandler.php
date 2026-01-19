<?php

namespace App\Blockchains\Api\CryptApi;

use App\Models\User;
use Exception;

class CryptApiHandler
{

    private User $user;

    private array $validCurrencies = [
        'trx', 'trc20_usdt'
    ];

    private string $fundWallet;
    private string $callbackUrl;

    public function __construct()
    {
        $this->fundWallet = config('network_tron.fund_wallet');
    }

    public static function init(): self
    {
        return new static();
    }

    public function setFundWallet(string $address): self
    {
        $this->fundWallet = $address;
        return $this;
    }

    public function setCallBackUrl(string $url): self
    {
        $this->callbackUrl = $url;
        return $this;
    }

    public function fromUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function currency(string $currency = 'usdt'): self
    {
        if (!in_array($currency, $this->validCurrencies)) {
            throw new Exception("Invalid Currency");
        }

        return $this;
    }



}
