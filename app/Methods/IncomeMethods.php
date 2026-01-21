<?php

namespace App\Methods;

use App\Models\Subscription;
use App\Models\User;
use App\Models\UserInvestment;

class IncomeMethods
{
    private User $user;
    private string $totalIncomeAmount;
    private string $type;

    public function __construct(User $user, string $totalIncomeAmount, string $type)
    {
        $this->user = $user;
        $this->totalIncomeAmount = $totalIncomeAmount;
        $this->type = $type;
    }

    public static function init(User $user, string $totalIncomeAmount): self
    {
        return new static($user, $totalIncomeAmount);
    }

    public function updateIncome(): string
    {
        $incomeUsd = $this->totalIncomeAmount;
        $type = $this->type;

        foreach (Subscription::where('user_id', $this->user->id)->where('is_active', true)->cursor() as $subscription) {


            $availableUsd = subDecimalStrings($subscription->max_income_limit, $subscription->earned_so_far, 2);

            if ($type === 'working'){
                $availableWorkingUsd = subDecimalStrings($subscription->working_income_limit, $subscription->working_earned_so_far, 2);
            }

            if ($type === 'passive'){
                $availablePassiveUsd = subDecimalStrings($subscription->passive_income_limit, $subscription->passive_earned_so_far, 2);
            }
            if ($availableUsd == '0.00') {

                $subscription->update([
                    'is_active' => false
                ]);

                continue;
            }
            if ($availableUsd < $incomeUsd) {
                $subscription->update([
                    'earned_so_far' => $subscription->max_income_limit,
                    'is_active' => false
                ]);
                $incomeUsd = subDecimalStrings($incomeUsd, $availableUsd, 2);

                /* Auto Renew the next level of the package */



            } else {
                $subscription->increment('earned_so_far', $incomeUsd);
                $incomeUsd = subDecimalStrings($incomeUsd, $incomeUsd, 2);
            }
            if ($incomeUsd == '0.00') {
                break;
            }
        }
        return subDecimalStrings($this->totalIncomeAmount, $incomeUsd, 2);
    }

    public function updateIncomeBySubscription(Subscription $subscription): ?string
    {
        $incomeUsd = $this->totalIncomeAmount;

        $availableUsd = subDecimalStrings($subscription->max_income_limit, $subscription->earned_so_far, 2);
        if ($availableUsd == '0.00') {
            $subscription->update([
                'is_active' => false
            ]);
            return null;
        }
        if ($availableUsd <= $incomeUsd) {
            $subscription->update([
                'earned_so_far' => $subscription->max_income_limit,
                'is_active' => false
            ]);
            $incomeUsd = subDecimalStrings($incomeUsd, $availableUsd, 2);
        } else {
            $subscription->increment('earned_so_far', $incomeUsd);
            $incomeUsd = subDecimalStrings($incomeUsd, $incomeUsd, 2);
        }


        return subDecimalStrings($this->totalIncomeAmount, $incomeUsd, 2);
    }

    public function updatePassiveIncomeByInvestment(UserInvestment $investment): ?string
    {
        $incomeUsd = $this->totalIncomeAmount;
        $availableUsd = subDecimalStrings($investment->passive_income_limit, $investment->passive_income, 2);

        if ($availableUsd == '0.00') {
            $investment->update([
                'is_passive_income_active' => false
            ]);
            return null;
        }
        if ($availableUsd <= $incomeUsd) {
            $investment->update([
                'passive_income' => $investment->passive_income_limit,
                'earned_so_far'=>$availableUsd,
                'is_passive_income_active' => false
            ]);
            $incomeUsd = subDecimalStrings($incomeUsd, $availableUsd, 2);
        } else {
            $investment->increment('passive_income', $incomeUsd);
            $investment->increment('earned_so_far', $incomeUsd);
            $incomeUsd = subDecimalStrings($incomeUsd, $incomeUsd, 2);
        }

        return subDecimalStrings($this->totalIncomeAmount, $incomeUsd, 2);
    }

    public function updateWorkingIncomeByInvestment(UserInvestment $investment): ?string
    {
        $incomeUsd = $this->totalIncomeAmount;
        $availableUsd = subDecimalStrings($investment->working_income_limit, $investment->working_income, 2);

        if ($availableUsd == '0.00') {
            $investment->update([
                'is_working_income_active' => false
            ]);
            return null;
        }
        if ($availableUsd <= $incomeUsd) {
            //$incomeUsd = subDecimalStrings($incomeUsd, $incomeUsd, 2);
            $investment->update([
                'working_income' => $investment->working_income_limit,
                'earned_so_far'=>$availableUsd,
                'is_working_income_active' => false
            ]);
            $incomeUsd = subDecimalStrings($incomeUsd, $availableUsd, 2);
        } else {
            $investment->increment('working_income', $incomeUsd);
            $investment->increment('earned_so_far', $incomeUsd);
            $incomeUsd = subDecimalStrings($incomeUsd, $incomeUsd, 2);
        }

        return subDecimalStrings($this->totalIncomeAmount, $incomeUsd, 2);
    }



}
