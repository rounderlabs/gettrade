<?php

namespace App\Console\Commands;

use App\Jobs\CreateLevelIncomeJob;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateLevelIncomeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'level:income {subscription_id}';

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

        $subscriptionId = $this->argument('subscription_id');
        $validator = Validator::make(['subscription_id' => $subscriptionId], [
            'subscription_id' => ['required', 'numeric', 'exists:subscriptions,id']
        ]);
        if ($validator->fails()) {
            $this->error("invalid subscription_id");
            exit();
        }

        $subscriptions = Subscription::where('id', '>=', $subscriptionId)->orderBy('id')->get();
        foreach ($subscriptions as $subscription) {
            CreateLevelIncomeJob::dispatch($subscription)->delay(now()->addSecond());
        }
    }
}
