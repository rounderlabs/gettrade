<?php

namespace App\Jobs;

use App\Events\Registered;
use App\Models\User;
use App\Methods\Downline;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PostRegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public User $sponsorUser;
    public string $position;
    public User $lastTreeLegUser;

    public $timeout = 240;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param User $sponsorUser
     * @param string $position
     */
    public function __construct(User $user, User $sponsorUser, string $position)
    {
        $this->onQueue('team');
        $this->user = $user;
        $this->sponsorUser = $sponsorUser;
        $this->position = $position;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->user->refresh();
        $this->lastTreeLegUser = Downline::init($this->user)->findLastChild($this->sponsorUser, $this->position);
        DB::transaction(function () {
            $this->user->binaryTree()->create([
                'sponsor_id' => $this->sponsorUser->id,
                'position' => $this->position,
                'parent_id' => $this->lastTreeLegUser->id
            ]);

            $this->user->update([
                'placed_into_tree' => true
            ]);
        });

        event(new Registered($this->user));

        UpdateTeamStatJob::dispatch($this->user)->delay(now()->addSecond());

    }

}
