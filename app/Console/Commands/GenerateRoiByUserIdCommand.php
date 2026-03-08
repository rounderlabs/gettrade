<?php

namespace App\Console\Commands;

use App\Jobs\CreateRoiIncomeJob;
use App\Models\RoiIncomeClosing;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class GenerateRoiByUserIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:roi-by-user {user_id} {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $incomeDate = $this->argument('date');
        $user_id = $this->argument('user_id');

        $validator = Validator::make(
            ['user_id'=>$user_id],
            ['user_id' => ['required', 'numeric', 'exists:users,id']],
            ['income_date' => $incomeDate],
            ['income_date' => ['required', 'date']]
        );

        if ($validator->fails()) {
            $this->error('❌ Invalid income_date. Format: YYYY-MM-DD');
            return SymfonyCommand::FAILURE;
        }

        $userActiveSubscriptions = Subscription::where('user_id', $user_id)->where('is_active', '=', 1)->where('created_at', '<', $incomeDate)->get();
        foreach ($userActiveSubscriptions as $subscription) {
            $closing = RoiIncomeClosing::firstOrCreate(['closing_date' => $incomeDate]);
            CreateRoiIncomeJob::dispatch($subscription, $closing)->delay(now()->addSecond());
        }
    }
}
