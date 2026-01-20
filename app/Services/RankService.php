<?php
namespace App\Services;

use App\Models\SiteSetting;
use App\Models\User;
use App\Models\UserRank;
use App\Models\UserLegBusiness;

class RankService
{
    public static function evaluate(User $user): ?int
    {
        $rules = SiteSetting::get('rank_rules', []);
        $currentRank = UserRank::where('user_id', $user->id)->value('rank') ?? 0;

        foreach ($rules as $rule) {
            if ($rule['rank'] <= $currentRank) {
                continue;
            }

            if (!self::passesRule($user, $rule)) {
                return null;
            }

            self::assignRank($user, $rule['rank']);
            return $rule['rank'];
        }

        return null;
    }

    protected static function passesRule(User $user, array $rule): bool
    {
        // ==========================
        // RANK 1 → LEG BUSINESS BASED
        // ==========================
        if ($rule['rank'] === 1) {
            $totalLegBusiness = UserLegBusiness::where('user_id', $user->id)
                ->sum('amount');

            return bccomp(
                    $totalLegBusiness,
                    $rule['level1_business'],
                    2
                ) >= 0;
        }

        // ==========================
        // RANK 2+ → DIRECT RANK HOLDERS
        // ==========================
        $directUserIds = getDirectUserIds($user->id);

        $qualified = UserRank::whereIn('user_id', $directUserIds)
            ->where('rank', $rule['required_direct_rank'])
            ->count();

        return $qualified >= $rule['required_direct_count'];
    }

    protected static function assignRank(User $user, int $rank): void
    {
        UserRank::updateOrCreate(
            ['user_id' => $user->id],
            ['rank' => $rank, 'achieved_at' => now()]
        );
    }
}
