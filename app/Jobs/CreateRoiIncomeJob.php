<?php

namespace App\Jobs;

use App\Methods\IncomeMethods;
use App\Models\Plan;
use App\Models\RoiIncomeClosing;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateRoiIncomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Subscription $subscription;
    private RoiIncomeClosing $roiIncomeClosing;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription, RoiIncomeClosing $roiIncomeClosing)
    {
        $this->onQueue('income');
        $this->subscription = $subscription->withoutRelations();
        $this->roiIncomeClosing = $roiIncomeClosing->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->subscription->refresh();
        $investmentAmount = $this->subscription->amount;
        $user = $this->subscription->user;

        if (!isUserActive($user)) {
            Log::warning("User is not active.");
            return;
        }

        if (!$this->subscription->is_active) {
            Log::warning("Subscription is not active.");
            return;
        }


        $userStop = userStop($user);
        if ($userStop->is_blocked) {
            return null;
        }

        if ($userStop->roi) {
            return null;
        }

        $userRoiIncome = $user->userRoiIncomes()->where('closing_date', $this->roiIncomeClosing->closing_date)->where('subscription_id', $this->subscription->id)->exists();

        if (!$userRoiIncome) {
            $plan = Plan::find($this->subscription->plan_id);
            $daysInMonth = Carbon::now()->daysInMonth;
            $incomeAmount = divDecimalStrings($plan->monthly_roi_amount, $daysInMonth, 2);

            if (is_null($incomeAmount)) {
                return;
            }

            $userRoiIncome = $user->userRoiIncomes()->create([
                'closing_date' => $this->roiIncomeClosing->closing_date,
                'subscription_id' => $this->subscription->id,
                'amount' => $investmentAmount,
                'income' => $incomeAmount,
            ]);

            $userIncomeStat = userIncomeStat($user);
            $userIncomeStat->increment('roi', $incomeAmount);
            $userIncomeStat->increment('total', $incomeAmount);

            $userIncomeOnHold = userIncomeOnHold($user);
            $userIncomeOnHold->increment('roi', $incomeAmount);
            $userIncomeOnHold->increment('total', $incomeAmount);

            $amount = $incomeAmount;
            $walletType = 'Income Wallet';
            $currency = 'INR';
            $txn_type = 'Credit';
            $remark = 'Daily Dividend (Fraction Of Monthly Dividend) Bonus of ₹ '.$amount.' has been Credited';
            CreateUserWalletLedgerJob::dispatch($user, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());

            dispatch( new GenerateLevelRoiIncomeJob($userRoiIncome))->delay(now());
        }
    }
}
