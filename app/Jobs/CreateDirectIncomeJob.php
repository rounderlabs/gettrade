<?php

namespace App\Jobs;

use App\Methods\IncomeMethods;
use App\Models\Plan;
use App\Models\SiteSetting;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserUsdWalletTransaction;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateDirectIncomeJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public UserUsdWalletTransaction $userUsdWalletTransaction;
    /**
     * @var mixed
     */
    public $user;
    private Subscription $subscription;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserUsdWalletTransaction $userUsdWalletTransaction, Subscription $subscription)
    {
        $this->userUsdWalletTransaction = $userUsdWalletTransaction;
        $this->user = $userUsdWalletTransaction->user;
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $directPercent = SiteSetting::get('direct_percent', 0);
        $directPercentDecimal = divDecimalStrings($directPercent, 100, 4);
        $plan = Plan::where('id',$this->subscription->plan_id )->first();
        $parentUser = $this->user->sponsor;
        if (is_null($parentUser) || !$this->isUserActive($parentUser)) {
            return null;
        }

        if (!haveActiveSubscriptions($parentUser,)) {
            return null;
        }


        $incomeAmount = multipleDecimalStrings($this->userUsdWalletTransaction->amount_in_usd, $directPercentDecimal, 4);

        $incomeReceived = IncomeMethods::init($parentUser, $incomeAmount)->updateIncome();

        if ($incomeReceived <= castDecimalString('0', 2)) {
            return;
        }
        $subscription = $this->userUsdWalletTransaction->subscription;

        $parentUser->userDirectIncomes()->create([
            'subscription_id' => $subscription->id,
            'income' => $incomeReceived
        ]);



        $userIncomeStat = userIncomeStat($parentUser);
        $userIncomeStat->increment('direct', $incomeReceived);
        $userIncomeStat->increment('total', $incomeReceived);

        $userIncomeOnHold = userIncomeOnHold($parentUser);
        $userIncomeOnHold->increment('direct', $incomeReceived);
        $userIncomeOnHold->increment('total', $incomeReceived);

        $amount = $incomeReceived;
        $walletType = 'Income Wallet';
        $currency = 'INR';
        $txn_type = 'Credit';
        $remark = 'Market earnings (Direct Bonus) of ₹ '.$amount.' has been Credited';
        CreateUserWalletLedgerJob::dispatch($parentUser, $walletType, $currency, $txn_type, $amount, $remark)->delay(now()->addSecond());


    }

    private function isUserActive(User $user): bool
    {
        return isUserActive($user);
    }
}
