<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\CryptoPrice;
use RuntimeException;

class CurrencyService
{
    /**
     * Convert amount between currencies (display only)
     */
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

        // ===============================
        // FIAT → FIAT
        // ===============================
        if (
            $fromCurrency->currency_type === 'fiat' &&
            $toCurrency->currency_type === 'fiat'
        ) {
            return self::fiatToFiat($amount, $from, $to);
        }

        // ===============================
        // FIAT → CRYPTO (DISPLAY)
        // ===============================
        if (
            $fromCurrency->currency_type === 'fiat' &&
            $toCurrency->currency_type === 'crypto'
        ) {
            return self::fiatToCrypto($amount, $to);
        }

        // ===============================
        // CRYPTO → FIAT (DISPLAY)
        // ===============================
        if (
            $fromCurrency->currency_type === 'crypto' &&
            $toCurrency->currency_type === 'fiat'
        ) {
            return self::cryptoToFiat($amount, $from);
        }

        throw new RuntimeException("Conversion not supported: {$from} → {$to}");
    }

    // --------------------------------------------------
    // FIAT → FIAT
    // --------------------------------------------------
    protected static function fiatToFiat(
        string $amount,
        string $from,
        string $to
    ): string {
        $rate = ExchangeRate::where('from_currency', $from)
            ->where('to_currency', $to)
            ->latest('effective_at')
            ->value('rate');

        if (! $rate) {
            throw new RuntimeException("Exchange rate missing: {$from} → {$to}");
        }

        return bcmul($amount, (string) $rate, self::fiatScale());
    }

    // --------------------------------------------------
    // FIAT → CRYPTO
    // amount INR → BTC
    // formula: amount / BTC_price
    // --------------------------------------------------
    protected static function fiatToCrypto(
        string $amount,
        string $cryptoCode
    ): string {
        $price = CryptoPrice::where('symbol', $cryptoCode)
            ->latest('fetched_at')
            ->value('price');

        if (! $price) {
            throw new RuntimeException("Crypto price missing: {$cryptoCode}");
        }

        return bcdiv($amount, (string) $price, self::cryptoScale());
    }

    // --------------------------------------------------
    // CRYPTO → FIAT
    // amount BTC → INR
    // formula: amount × BTC_price
    // --------------------------------------------------
    protected static function cryptoToFiat(
        string $amount,
        string $cryptoCode
    ): string {
        $price = CryptoPrice::where('symbol', $cryptoCode)
            ->latest('fetched_at')
            ->value('price');

        if (! $price) {
            throw new RuntimeException("Crypto price missing: {$cryptoCode}");
        }

        return bcmul($amount, (string) $price, self::fiatScale());
    }

    protected static function fiatScale(): int
    {
        return 2;
    }

    protected static function cryptoScale(): int
    {
        return 8;
    }
}
