<?php


namespace App\Methods;


use App\Models\Team;
use App\Models\Tree;
use App\Models\User;
use App\Models\UserLevelAchievementStat;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;


class TeamStat
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
        $startOfWeek = Carbon::now()->startOfWeek(CarbonInterface::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(CarbonInterface::SUNDAY);

        $startDate =  $startOfWeek->toDateString(); // e.g. 2025-11-10
        $endDate =  $endOfWeek->toDateString();
        $userWeeklyTeamWithBusinessStat = userWeeklyTeamWithBusinessStat($user, $startDate, $endDate);
        if ($sponsorId) {
            $this->updateTeamCount($sponsorId, $activeTeamData ? 'active_direct' : 'direct');
            $this->updateTeamCount($sponsorId, $activeTeamData ? 'active_total' : 'total');
            $userWeeklyTeamWithBusinessStat->increment('direct_active', 1);
        }

        while (true) {
            $parentUser = Tree::select('sponsor_id')->where('user_id', $sponsorId)->first();
            if (!$parentUser) {
                return true;
            }
            $parentUserId = $parentUser->sponsor_id;
            $this->updateTeamCount($parentUserId, $activeTeamData ? 'active_total' : 'total');
            $userWeeklyTeamWithBusinessStat->increment('team_active', 1);
            $sponsorId = $parentUserId;
        }
    }

    private function updateTeamCount($userId, $field)
    {
        $team = Team::where('user_id', $userId)->first();
        if ($team) {
            Team::create([
                'user_id' => $userId,
                'direct' => $field == 'direct' ? $team->direct + 1 : $team->direct,
                'active_direct' => $field == 'active_direct' ? $team->active_direct + 1 : $team->active_direct,
                'total' => $field == 'total' ? $team->total + 1 : $team->total,
                'active_total' => $field == 'active_total' ? $team->active_total + 1 : $team->active_total,
            ]);
            $team->delete();
        }

        $team = Team::where('user_id', $userId)->first();

        $user = User::find($userId);

        if ($field == 'active_direct' && $team->active_direct >= 5) {
            UserLevelAchievementStat::firstOrCreate(
                [
                    'user_id' => $userId,
                    'current_level' => subDecimalStrings($team->active_direct, '4'),
                    'status' => 1
                ]
            );
        }

    }

    public function updateUnlinkStatToUpline($activeTeamData = false): bool
    {
        $this->user->refresh();
        $user = $this->user;
        $sponsorId = $user->sponsor_id;
        if ($sponsorId) {
            $this->updateUnlinkTeamCount($sponsorId, $activeTeamData ? 'active_direct' : 'direct');
            $this->updateUnlinkTeamCount($sponsorId, $activeTeamData ? 'active_total' : 'total');
        }

        while (true) {
            $parentUser = Tree::select('sponsor_id')->where('user_id', $sponsorId)->first();
            if (!$parentUser) {
                return true;
            }
            $parentUserId = $parentUser->sponsor_id;
            $this->updateUnlinkTeamCount($parentUserId, $activeTeamData ? 'active_total' : 'total');
            $sponsorId = $parentUserId;
        }
    }


    private function updateUnlinkTeamCount($userId, $field)
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
