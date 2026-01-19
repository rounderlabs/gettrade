<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserDailyBusinessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public string $type;
    public string $amount;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $type, string $amount)
    {
        $this->user = $user;
        $this->type = $type;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;
        $type = $this->type;
        $amount = castDecimalString($this->amount, 2);
        $today = now()->format('Y-m-d');
        $userDailyBusinessStat = userDailyBusinessStat($user, $today);

        if ($type === 'self_business') {
            $userDailyBusinessStat->increment('self_business', $amount);
        }elseif ($type === 'direct_business') {
            $userDailyBusinessStat->increment('direct_business', $amount);
        }elseif ($type === 'team_business') {
            $userDailyBusinessStat->increment('team_business', $amount);
        }
        $userDailyBusinessStat->refresh();

        //we can set this dynamic if needful from database

        $magic_achievement = 500;

        if(addDecimalStrings($userDailyBusinessStat->self_business, $userDailyBusinessStat->direct_business, 2 ) >= $magic_achievement) {

            $userDailyBusinessStat->update(['is_achieved' =>1]);
        }


    }
}
