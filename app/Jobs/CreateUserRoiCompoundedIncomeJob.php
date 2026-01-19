<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserRoiCompoundedClosing;
use App\Models\UserRoiCompoundedIncomes;
use App\Models\UserRoiCompounding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateUserRoiCompoundedIncomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private UserRoiCompounding $roiCompounding;
    private UserRoiCompoundedClosing $compoundedClosing;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserRoiCompounding $roiCompounding, UserRoiCompoundedClosing $compoundedClosing)
    {
        $this->onQueue('income');
        $this->roiCompounding = $roiCompounding;
        $this->compoundedClosing = $compoundedClosing;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->roiCompounding->refresh();
        $this->compoundedClosing->refresh();

        $user = User::find($this->roiCompounding->user_id);
        if (!isUserActive($user)) {
            return;
        }


//        $userExist = UserRoiCompoundedIncomes::where('user_id', $user->id)->where('closing_date', $this->compoundedClosing->closing_date)->first();
//        if (!$userExist) {
//            return;
//        }

        if (!is_null($user->userRefundSubscription)) {
            if ($user->userRefundSubscription->status != 'canceled') {
                return;
            }
        }

        $userStop = userStop($user);
        if ($userStop->compounding === 1) {
            return;
        }

        $userSubscription = $user->subscriptions()->with('plan')->first();

        $compoundingPercent = $userSubscription->plan->daily_roi_percent_decimal;

        $incomeUsd = multipleDecimalStrings($this->roiCompounding->compounded_amount, $compoundingPercent, 4);

//        $receivedIncome = IncomeMethods::init($user, $incomeUsd)->updateIncome();
//        if (is_null($receivedIncome)) {
//            return;
//        }

        UserRoiCompoundedIncomes::create([
            'user_id' => $user->id,
            'closing_date'=>$this->compoundedClosing->closing_date,
            'principal_compounded_amount'=>$this->roiCompounding->compounded_amount,
            'amount'=>$incomeUsd
        ]);

        $userIncomeWallet = userIncomeWallet($user);
        $userIncomeWallet->increment('balance', $incomeUsd);

        $userIncomeStat = userIncomeStat($user);
        $userIncomeStat->increment('compounded', $incomeUsd);
        $userIncomeStat->increment('total', $incomeUsd);

        $this->roiCompounding->increment('compounded_amount', $incomeUsd);

        $amount = $incomeUsd;
        $walletType = 'Income Wallet';
        $currency = 'USDT';
        $txn_type = 'Credit';
        $remark = 'Compounding Income of $'.$amount.' has been Credited';
        CreateUserWalletLedgerJob::dispatch($user, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());


    }
}
