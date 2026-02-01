<?php

namespace App\Services\Crypto;

interface CryptoPriceProvider
{
    public function fetchPrices(): array;
}
