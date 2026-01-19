<?php


namespace App\Methods;


use App\Models\Subscription;

class SubscriptionMethod
{
    private Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public static function init(Subscription $subscription)
    {
        return new static($subscription);
    }

    public function canNextIncomeBeAdded(string $amount): bool
    {
        if (addDecimalStrings($this->subscription->earned_so_far, $amount, 2) <= $this->subscription->max_amount_limit_usd) {
            return true;
        }
        return false;
    }

    public function incrementEarnedSoFar(string $amount)
    {
        $this->subscription->increment('earned_so_far', $amount);
    }

    public function deleteSubscription()
    {
        $this->subscription->delete();
    }

}
