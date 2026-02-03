<?php

namespace App\Services\Crypto;

use Illuminate\Support\Facades\Http;

class CoinGeckoProvider implements CryptoPriceProvider
{
    public function fetchPrices(): array
    {
        $response = Http::get(
            'https://api.coingecko.com/api/v3/simple/price',
            [
                'ids' => 'bitcoin,ethereum,tether',
                'vs_currencies' => 'usd,inr',
            ]
        )->json();

        return [
            // BTC
            ['symbol' => 'BTC', 'pair' => 'USD', 'price' => $response['bitcoin']['usd']],
            ['symbol' => 'BTC', 'pair' => 'INR', 'price' => $response['bitcoin']['inr']],

            // ETH
            ['symbol' => 'ETH', 'pair' => 'USD', 'price' => $response['ethereum']['usd']],
            ['symbol' => 'ETH', 'pair' => 'INR', 'price' => $response['ethereum']['inr']],

            // USDT
            ['symbol' => 'USDT', 'pair' => 'USD', 'price' => $response['tether']['usd']],
            ['symbol' => 'USDT', 'pair' => 'INR', 'price' => $response['tether']['inr']],
        ];
    }
}
