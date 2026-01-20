<?php

namespace App\Services;

use App\Models\SiteSetting;

class CommissionService
{
    /**
     * Direct commission (level 0)
     */
    public static function direct(float $amount): float
    {
        $percent = (float) SiteSetting::get('direct_percent', 0);
        return self::calculateAmount($amount, $percent);
    }

    /**
     * Level-wise commission
     */
    public static function level(float $amount, int $level): float
    {
        $levels = SiteSetting::get('level_percentages', []);

        if (!isset($levels[$level])) {
            return 0;
        }

        return self::calculateAmount($amount, (float) $levels[$level]);
    }

    /**
     * Shared calculation logic
     */
    protected static function calculateAmount(float $amount, float $percent): float
    {
        return round(($amount * $percent) / 100, 2);
    }
}
