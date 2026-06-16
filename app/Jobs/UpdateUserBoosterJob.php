<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Subscription;
use App\Models\UserBoosterStat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserBoosterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user->withoutRelations();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;

        // Get all active subscriptions for the user
        $subscriptions = Subscription::where('user_id', $user->id)
            ->where('is_active', 1)
            ->get();

        // Get direct referred user IDs
        $directUserIds = getDirectUserIds($user->id);

        foreach ($subscriptions as $subscription) {
            $existingBooster = UserBoosterStat::where('subscription_id', $subscription->id)->first();
            
            $startOfWindow = $subscription->created_at;
            $endOfWindow = $subscription->created_at->copy()->addDays(7);

            // Query directs who bought package of same or above amount within the 7-day window
            $qualifiedDirectsCount = 0;
            if (!empty($directUserIds)) {
                $qualifiedDirectsCount = Subscription::whereIn('user_id', $directUserIds)
                    ->where('is_active', 1)
                    ->whereBetween('created_at', [$startOfWindow, $endOfWindow])
                    ->where('amount', '>=', $subscription->amount)
                    ->distinct('user_id')
                    ->count('user_id');
            }

            $isBoosterActive = $qualifiedDirectsCount >= 2;
            $activatedAt = null;

            if ($existingBooster && $existingBooster->is_booster_active) {
                // If it was already active, preserve its activation time
                $isBoosterActive = true;
                $activatedAt = $existingBooster->booster_activated_at;
            } elseif ($isBoosterActive) {
                $activatedAt = now();
            }

            UserBoosterStat::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id,
                ],
                [
                    'active_direct_count' => $qualifiedDirectsCount,
                    'is_booster_active' => $isBoosterActive ? 1 : 0,
                    'booster_activated_at' => $activatedAt,
                ]
            );
        }
    }
}
