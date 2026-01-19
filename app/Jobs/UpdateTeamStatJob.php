<?php

namespace App\Jobs;

use App\Models\User;
use App\Methods\TeamStat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTeamStatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    public $timeout = 60;

    public bool $activeTeam;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, bool $activeTeam = false)
    {
        $this->user = $user->withoutRelations();
        $this->activeTeam = $activeTeam;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        TeamStat::init($this->user)->updateStatToUpline($this->activeTeam);
    }
}
