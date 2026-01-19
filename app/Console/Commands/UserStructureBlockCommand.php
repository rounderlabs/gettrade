<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserLevelStat;
use Illuminate\Console\Command;

class UserStructureBlockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structure:block {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user_id = $this->argument('user_id');
        $allDownLines = UserLevelStat::where('user_id', $user_id)->get();
        foreach ($allDownLines as $allDownLine) {
            $user = User::where('id', $allDownLine->downline_user_id)->first();
            $user->update(['is_blocked' => true]);
        }

    }
}
