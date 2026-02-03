<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\CryptoPrice;
use RuntimeException;

class CurrencyService
{
    public static function convert(
        string $amount,
        string $from,
        string $to
    ): string {
        if ($from === $to) {
            return $amount;
        }

        $fromCurrency = Currency::where('code', $from)->firstOrFail();
        $toCurrency   = Currency::where('code', $to)->firstOrFail();

        // FIAT → FIAT
        if ($fromCurrency->currency_type === 'fiat' && $toCurrency->currency_type === 'fiat') {
            return self::fiatToFiat($amount, $from, $to);
        }

        // FIAT → CRYPTO (INR → BTC)
        if ($fromCurrency->currency_type === 'fiat' && $toCurrency->currency_type === 'crypto') {
            return self::fiatToCrypto($amount, $from, $to);
        }

        // CRYPTO → FIAT (BTC → INR)
        if ($fromCurrency->currency_type === 'crypto' && $toCurrency->currency_type === 'fiat') {
            return self::cryptoToFiat($amount, $from, $to);
        }

        throw new RuntimeException("Conversion not supported: {$from} → {$to}");
    }

    // --------------------------------------------------
    // FIAT → FIAT
    // --------------------------------------------------
    protected static function fiatToFiat(string $amount, string $from, string $to): string
    {
        $rate = ExchangeRate::where('from_currency', $from)
            ->where('to_currency', $to)
            ->latest('effective_at')
            ->value('rate');

        if (! $rate) {
            throw new RuntimeException("Exchange rate missing: {$from} → {$to}");
        }

        return bcmul($amount, (string)$rate, 2);
    }

    // --------------------------------------------------
    // FIAT → CRYPTO (INR → BTC)
    // amount / crypto_price_in_fiat
    // --------------------------------------------------
    protected static function fiatToCrypto(string $amount, string $fiat, string $crypto): string
    {
        $price = CryptoPrice::where('symbol', $crypto)
            ->where('pair', $fiat)
            ->latest('fetched_at')
            ->value('price');

        if (! $price) {
            throw new RuntimeException("Crypto price missing: {$crypto}/{$fiat}");
        }

        return bcdiv($amount, (string)$price, 8);
    }

    // --------------------------------------------------
    // CRYPTO → FIAT (BTC → INR)
    // amount × crypto_price_in_fiat
    // --------------------------------------------------
    protected static function cryptoToFiat(string $amount, string $crypto, string $fiat): string
    {
        $price = CryptoPrice::where('symbol', $crypto)
            ->where('pair', $fiat)
            ->latest('fetched_at')
            ->value('price');

        if (! $price) {
            throw new RuntimeException("Crypto price missing: {$crypto}/{$fiat}");
        }

        return bcmul($amount, (string)$price, 2);
    }
}
