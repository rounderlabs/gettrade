<?php

namespace App\Services\CoinPayment;

use CoinpaymentsAPI;

class CoinPaymentsService
{
    protected CoinpaymentsAPI $client;

    public function __construct()
    {
        if ((int) site_setting('coinpayments_enabled') !== 1) {
            throw new \Exception('CoinPayments is disabled');
        }

        $publicKey  = site_setting('coinpayments_public_key');
        $privateKey = site_setting('coinpayments_private_key');

        if (!$publicKey || !$privateKey) {
            throw new \Exception('CoinPayments keys are not configured');
        }

        $this->client = new CoinpaymentsAPI(
            $privateKey,
            $publicKey,
            'json'
        );
    }

    public function createTransaction(array $data): array
    {
        return $this->client->CreateTransaction($data);
    }
}
