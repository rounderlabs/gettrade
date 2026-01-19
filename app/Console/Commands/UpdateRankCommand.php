<?php

namespace App\Console\Commands;

use App\Models\UserInvestment;
use App\Methods\RankMethod;
use Illuminate\Console\Command;

class UpdateRankCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update User Rank';

    public function handle()
    {
        $investments = UserInvestment::with('user')->orderBy('id')->get();

        foreach ($investments as $investment) {
            $user = $investment->user;

            // Safety check
            if (!$user) {
                continue;
            }

            RankMethod::init($user)->generateRank();
        }

        $this->info('✅ Rank Command Executed Successfully!');
        return self::SUCCESS;
    }
}
