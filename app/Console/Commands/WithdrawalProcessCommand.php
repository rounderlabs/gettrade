<?php

namespace App\Console\Commands;

use App\Jobs\ProcessUsdWithdrawalJob;
use App\Jobs\ProcessUsdWithdrawalUsingAPIlJob;
use App\Jobs\ProcessWithdrawalJob;
use App\Models\WithdrawalHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class   WithdrawalProcessCommand extends Command
{
    protected $withdrawHistoryId;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'withdrawal:process {withdraw_history_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending withdrawal';

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
     */
    public function handle()
    {
        $this->withdrawHistoryId = $this->argument('withdraw_history_id');

        $validator = Validator::make(['history_id' => $this->withdrawHistoryId], [
            'history_id' => ['required', 'numeric', 'exists:withdrawal_histories,id']
        ]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first('history_id'));
        }
        $withdrawalHistory = WithdrawalHistory::find($this->withdrawHistoryId);
        if ($withdrawalHistory->status !== "pending") {
            $this->error("Withdrawal History is not pending");
            exit();
        }

//        NodeProcessWithdrawalJob::dispatch($withdrawalHistory)->delay(now()->addSecond());
        ProcessUsdWithdrawalUsingAPIlJob::dispatch($withdrawalHistory)->delay(now()->addSecond());
        $this->info("Withdrawal processed successfully");
        exit();
    }
}
