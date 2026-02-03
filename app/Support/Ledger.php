<?php

namespace App\Support;

use App\Jobs\CreateUserWalletLedgerJob;
use App\Models\User;

class Ledger
{
    public static function record(
        User $user,
        string $walletType,
        string $txnType,      // Credit / Debit
        string|float $amountBase, // INR
        string $remarks,
        array $meta = []
    ): void {
        CreateUserWalletLedgerJob::dispatch(
            $user,
            $walletType,
            'INR',
            $txnType,
            castDecimalString($amountBase, 2),
            $remarks,
            $meta
        );
    }
}
