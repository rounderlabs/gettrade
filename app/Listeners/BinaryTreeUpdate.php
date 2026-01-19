<?php

namespace App\Listeners;

use App\Events\Registered;
use App\Jobs\UpdateTeamStatJob;
use App\Models\User;
use App\Methods\Downline;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BinaryTreeUpdate implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
        $sponsorUser = User::find($user->sponsor_id);
        if (is_null($sponsorUser)) {
            return;
        }

        $position = $user->position;

        $lastTreeLegUser = Downline::init($user)->findLastChild($sponsorUser, $position);
        $user->binaryTree()->create([
            'sponsor_id' => $sponsorUser->id,
            'position' => $position,
            'parent_id' => $lastTreeLegUser->id
        ]);

        $user->update([
            'placed_into_tree' => true
        ]);


        UpdateTeamStatJob::dispatch($user)->delay(now()->addSecond());
    }
}
