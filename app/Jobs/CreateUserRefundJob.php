<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Models\User;
use App\Models\UserRefundSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateUserRefundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public UserRefundSubscription $refundSubscription;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserRefundSubscription $refundSubscription)
    {
       $this->refundSubscription = $refundSubscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $refundRequest = $this->refundSubscription;
        $user = User::find($refundRequest->user_id);

    }
}
