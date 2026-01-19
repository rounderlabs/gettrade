<?php


namespace App\Methods;


use App\Models\User;
use App\Models\UserLevel;
use App\Models\UserLevelStat;

class UserLevelMethods
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

    public function eachParent(callable $callback)
    {
        $user = $this->user;
        for ($level = 1; $level <= 6; $level++) {
            $parentUser = $user->sponsor;
            if (is_null($parentUser)) {
                return;
            }
            $callback($parentUser->withoutRelations(), $level);
            $user = $parentUser;
        }
    }

    public function eachSponsor(callable $callback)
    {
        $this->user->refresh();
        $user = $this->user;

        $level = 1;
        while (true) {
            $parentUser = $user->sponsor;
            if (is_null($parentUser)) {
                return;
            }
            $callback($parentUser->withoutRelations(), $level);
            $user = $parentUser;
            $level++;
        }
    }

    public function eachSponsorV1(callable $callback)
    {
        $this->user->refresh();
        $user = $this->user;

        $level = 1;
        while (true) {
            $parentUser = $user->sponsor;
            if (is_null($parentUser)) {
                return;
            }
            $exitResponse = $callback($parentUser->withoutRelations(), $level);
            if ($exitResponse) {
                break;
            }
            $user = $parentUser;
            $level++;
        }
    }

    public function updateUplineStats($maxLevels = 20): bool
    {
        $user = $this->user;

        for ($i = 1; $i <= 20; $i++) {
            $parentUser = $user->sponsor;
            if (is_null($parentUser)) {
                return true;
            }
            $this->createUserLevelStat($parentUser, $i);
            $user = $parentUser;
        }
        return true;
    }

    private function createUserLevelStat(User $parentUser, $level)
    {
        UserLevelStat::updateOrCreate(
            [
                'user_id' => $parentUser->id,
                'downline_user_id' => $this->user->id,
                'level' => $level,
            ],
            [
                'updated_at' => now(),
            ]
        );
    }


    private function incrementTeamCount(User $user, $level, $field)
    {
        $userLevel = UserLevel::firstOrCreate(
            ['user_id' => $user->id, 'level' => $level],
            ['team' => 0, 'active_team' => 0, 'users' => collect([])]
        );

        $this->addUserInParentLevelCollection($userLevel);
        $userLevel->increment($field);

    }

    public function addUserInParentLevelCollection(UserLevel $userLevel)
    {
        $usersCollection = $userLevel->users;
        $usersCollection->push($this->user->id);
        $userLevel->update([
            'users' => $usersCollection
        ]);
    }


}
