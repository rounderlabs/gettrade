<?php

namespace App\Jobs;

use App\Models\DepositTransaction;
use App\Methods\PurchaseMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public DepositTransaction $depositTransaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(DepositTransaction $depositTransaction)
    {
        $this->depositTransaction = $depositTransaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        PurchaseMethod::fromTransaction($this->depositTransaction)->init();
    }
}
