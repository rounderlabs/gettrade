<?php

namespace App\Console\Commands;

use App\Models\CryptoPrice;
use App\Services\Crypto\CryptoPriceProvider;
use Illuminate\Console\Command;

class FetchCryptoPrices extends Command
{
    protected $signature = 'crypto:fetch-prices';
    protected $description = 'Fetch crypto prices';

    public function handle(CryptoPriceProvider $provider)
    {
        foreach ($provider->fetchPrices() as $item) {
            CryptoPrice::updateOrCreate(
                [
                    'symbol' => $item['symbol'],
                    'pair' => 'USD',
                ],
                [
                    'price' => $item['price'],
                    'fetched_at' => now(),
                ]
            );
        }

        $this->info('Crypto prices updated');
    }
}

