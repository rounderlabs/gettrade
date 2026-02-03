<?php

namespace App\Console\Commands;

use App\Models\CryptoPrice;
use App\Services\Crypto\CryptoPriceProvider;
use Illuminate\Console\Command;

class FetchCryptoPrices extends Command
{
    protected $signature = 'crypto:fetch-prices';
    protected $description = 'Fetch crypto prices (USD + INR)';

    public function handle(CryptoPriceProvider $provider): int
    {
        foreach ($provider->fetchPrices() as $item) {
            CryptoPrice::updateOrCreate(
                [
                    'symbol' => $item['symbol'],
                    'pair'   => $item['pair'],
                ],
                [
                    'price' => $item['price'],
                    'fetched_at' => now(),
                ]
            );
        }

        $this->info('Crypto prices updated (USD & INR)');
        return self::SUCCESS;
    }
}
