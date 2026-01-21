<?php


namespace App\Services\Income;

use App\Models\Subscription;

class IncomeService
{
    /**
     * Credit WORKING income (Direct Bonus)
     * Limit: 3x of OWN subscription
     */
    public static function creditWorkingIncome(
        Subscription $subscription,
        string       $amountUsd
    ): string
    {

        if (!$subscription->is_active) {
            return '0.00';
        }

        $available = subDecimalStrings(
            $subscription->working_income_limit,
            $subscription->working_earned_so_far,
            2
        );

        if ($available <= 0) {
            $subscription->update(['is_active' => false]);
            return '0.00';
        }

        $credit = min($available, $amountUsd);

        $subscription->increment('working_earned_so_far', $credit);
        $subscription->increment('earned_so_far', $credit);

        if ($credit == $available) {
            $subscription->update(['is_active' => false]);
        }

        return castDecimalString($credit, 2);
    }

    /**
     * Credit PASSIVE income (ROI / Level)
     * Limit: 2x of OWN subscription
     */
    public static function creditPassiveIncome(
        Subscription $subscription,
        string       $amountUsd
    ): string
    {

        if (!$subscription->is_active) {
            return '0.00';
        }

        $available = subDecimalStrings(
            $subscription->passive_income_limit,
            $subscription->passive_earned_so_far,
            2
        );

        if ($available <= 0) {
            $subscription->update(['is_active' => false]);
            return '0.00';
        }

        $credit = min($available, $amountUsd);

        $subscription->increment('passive_earned_so_far', $credit);
        $subscription->increment('earned_so_far', $credit);

        if ($credit == $available) {
            $subscription->update(['is_active' => false]);
        }

        return castDecimalString($credit, 2);
    }
}
