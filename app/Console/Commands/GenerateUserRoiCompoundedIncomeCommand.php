<?php

namespace App\Console\Commands;

use App\Jobs\GenerateUserRoiCompoundedIncomeJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class GenerateUserRoiCompoundedIncomeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:compounding {income_date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Compounding Income';

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
        $incomeDate = $this->argument('income_date');
        $validator = Validator::make(['income_date' => $incomeDate], [
            'income_date' => ['required', 'date']
        ]);
        if ($validator->fails()) {
            $this->error("Invalid date");
            exit();
        }

        GenerateUserRoiCompoundedIncomeJob::dispatch($incomeDate)->delay(now()->addSecond());
    }
}
