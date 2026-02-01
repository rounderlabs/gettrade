<?php

namespace App\Support;

use App\Services\CurrencyService;
use App\Models\Currency;
use Throwable;

class Money
{
    /**
     * Convert & format amount for display
     */
    public static function display(
        string $amount,
        string $toCurrency,
        string $fromCurrency = 'INR'
    ): array {
        try {
            $converted = CurrencyService::convert(
                $amount,
                $fromCurrency,
                $toCurrency
            );
        } catch (Throwable $e) {
            // 🔒 fallback to base currency
            $converted = $amount;
            $toCurrency = $fromCurrency;
        }

        $currency = Currency::where('code', $toCurrency)->first();

        return [
            'raw'       => $converted,
            'formatted' => self::format($converted, $currency),
            'symbol'    => $currency->symbol ?? '₹',
            'code'      => $currency->code ?? 'INR',
            'type'      => $currency->currency_type ?? 'fiat',
        ];
    }

    protected static function format(string $amount, ?Currency $currency): string
    {
        $decimals = $currency && $currency->currency_type === 'crypto'
            ? 8
            : 2;

        return number_format((float) $amount, $decimals);
    }
}
