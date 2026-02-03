<?php

namespace App\Http\Controllers\Concerns;

use App\Services\CurrencyService;

trait DisplaysCurrency
{
    protected function displayAmount(string|float|null $amountBase, $user): string
    {


        $baseCurrency = 'INR';
        $userCurrency = $user->preferred_currency ?? 'INR';

        if ($userCurrency === $baseCurrency) {
            return number_format((float) $amountBase, 2);
        }

        return CurrencyService::convert(
            (string) $amountBase,
            $baseCurrency,
            $userCurrency
        );
    }

}
