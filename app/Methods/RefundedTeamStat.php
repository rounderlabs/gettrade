<?php


namespace App\Methods;

use App\Models\Team;
use App\Models\Tree;
use App\Models\User;

class RefundedTeamStat
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
            continue;
        }
    }

    public function updateStatToUpline($activeTeamData = false): bool
    {
        $this->user->refresh();
        $user = $this->user;
        $sponsorId = $user->sponsor_id;
        if ($sponsorId) {
            $this->updateRefundedTeamCount($sponsorId, $activeTeamData ? 'active_direct' : 'direct');
            $this->updateRefundedTeamCount($sponsorId, $activeTeamData ? 'active_total' : 'total');
        }

        while (true) {
            $parentUser = Tree::select('sponsor_id')->where('user_id', $sponsorId)->first();
            if (!$parentUser) {
                return true;
            }
            $parentUserId = $parentUser->sponsor_id;
            $this->updateRefundedTeamCount($parentUserId, $activeTeamData ? 'active_total' : 'total');
            $sponsorId = $parentUserId;
        }
    }

    private function updateRefundedTeamCount($userId, $field)
    {
        $team = Team::where('user_id', $userId)->first();
        if ($team) {
            Team::create([
                'user_id' => $userId,
                'direct' => $field == 'direct' ? $team->direct - 1 : $team->direct,
                'active_direct' => $field == 'active_direct' ? $team->active_direct - 1 : $team->active_direct,
                'total' => $field == 'total' ? $team->total - 1 : $team->total,
                'active_total' => $field == 'active_total' ? $team->active_total - 1 : $team->active_total,
            ]);
            $team->delete();
        }

    }
}
