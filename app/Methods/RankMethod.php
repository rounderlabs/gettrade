<?php

namespace App\Methods;

use App\Models\User;
use App\Models\UserRewardRank;

class RankMethod
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function init(User $user): self
    {
        return new static($user);
    }

    public function generateRank(): void
    {
        $user = $this->user;

        // Stop early if user not eligible
        if (!isUserActive($user) || !haveActiveSubscriptions($user)) {
            return;
        }

        $currentRank = userRewardRank($user);
        $rankConditions = config('rank');

        // If user has no current rank record, start at rank 1
        if (!$currentRank || $currentRank->rank_id === 0) {
            $this->updateAchievedRank($user, 1);
            return;
        }

        // Loop through ranks backwards (highest → lowest)
        for ($i = count($rankConditions) - 1; $i >= 0; $i--) {
            $rankCondition = $rankConditions[$i];

            // Skip if already achieved this or higher
            if ($currentRank->rank_id >= $rankCondition['achievable_rank']) {
                break;
            }

            // --- Team & Business Checks ---
            $userTeam = $user->team;
            $legs = $user->userLegBusinesses()->orderByDesc('usd')->get();

            $firstUsd = $legs->get(0)?->usd ?? '0';
            $secondUsd = $legs->get(1)?->usd ?? '0';
            $remainingUsd = $legs->skip(2)->sum('usd');

            // Weighted shares
            $firstShare = multipleDecimalStrings($firstUsd, '0.40', 2);
            $secondShare = multipleDecimalStrings($secondUsd, '0.40', 2);
            $remainingShare = multipleDecimalStrings($remainingUsd, '0.20', 2);

            $totalShare = addDecimalStrings(
                addDecimalStrings($firstShare, $secondShare),
                $remainingShare
            );

            // --- Rank Qualification ---
            if (
                $userTeam->active_direct >= $rankCondition['minimum_direct'] &&
                bccomp($totalShare, $rankCondition['required_business'], 2) >= 0
            ) {
                $this->updateAchievedRank($user, $rankCondition['achievable_rank']);
                break;
            }
        }
    }

    private function updateAchievedRank(User $user, int $rankId): void
    {
        UserRewardRank::updateOrCreate(
            ['user_id' => $user->id],
            ['rank' => $rankId, 'is_achieved' => 1]
        );
    }
}
