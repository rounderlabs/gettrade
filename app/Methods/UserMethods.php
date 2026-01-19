<?php


namespace App\Methods;


use App\Models\Champion;
use App\Models\Subscription;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;

class UserMethods
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

    public function isUserActive(): bool
    {
        return !is_null($this->user->userActive);
    }

    public function isChampion(): bool
    {
        return !is_null($this->user->champion);
    }

    public function createChampion(Subscription $subscription): bool
    {
        $parentSubscription = $this->user->subscriptions()->where('is_active', 1)->orderByDesc('id')->first();
        if (is_null($parentSubscription) || $parentSubscription->id > $subscription->id) {
            return false;
        }
        $newSubscriptionDate = Carbon::parse($subscription->created_at)->subDays(7);
        if (Carbon::parse($parentSubscription->created_at)->unix() >= $newSubscriptionDate->unix()) {
            $team = Team::where('user_id', $this->user->id)->first();
            if ($team->active_direct >= 5 && !$this->isChampion()) {
                Champion::updateOrCreate(['user_id' => $this->user->id], ['is_active' => 1]);
                return true;
            }
        }
        return false;
    }

    public function createBonanza(Subscription $subscription): bool
    {
        $parentSubscription = $this->user->subscriptions()->where('is_active', 1)->orderByDesc('id')->first();
        if (is_null($parentSubscription) || $parentSubscription->id > $subscription->id) {
            return false;
        }

        return true;
    }


}
