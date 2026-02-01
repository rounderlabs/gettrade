<?php

namespace App\Services\Crypto;
use App\Services\Crypto\CryptoPriceProvider;
use Illuminate\Support\Facades\Http;

class CoinGeckoProvider implements CryptoPriceProvider
{
    public function fetchPrices(): array
    {
        $response = Http::get(
            'https://api.coingecko.com/api/v3/simple/price',
            [
                'ids' => 'bitcoin,ethereum,tether',
                'vs_currencies' => 'usd',
            ]
        )->json();

        return [
            ['symbol' => 'BTC',  'price' => $response['bitcoin']['usd']],
            ['symbol' => 'ETH',  'price' => $response['ethereum']['usd']],
            ['symbol' => 'USDT', 'price' => $response['tether']['usd']],
        ];
    }
}
