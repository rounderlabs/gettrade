<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $plans = [
            ['name' => 'Basic', 'amount' => '3500.00','monthly_roi_amount'=>'1300', 'tenure'=>'27', 'maturity_tenure'=>'28', 'maturity_amount'=>'5250.00', 'principle_amount'=>'5250.00', 'allow_to_user'=>'0'],
            ['name' => 'Basic', 'amount' => '3500.00', 'monthly_roi_amount'=>'1300',  'tenure'=>'27', 'maturity_tenure'=>'28', 'maturity_amount'=>'5250.00', 'principle_amount'=>'5250.00', 'allow_to_user'=>'0'],
            ['name' => 'Basic', 'amount' => '3500.00', 'monthly_roi_amount'=>'1300',  'tenure'=>'27', 'maturity_tenure'=>'28', 'maturity_amount'=>'5250.00', 'principle_amount'=>'5250.00', 'allow_to_user'=>'0'],
            ['name' => 'Basic', 'amount' => '3500.00', 'monthly_roi_amount'=>'1300',  'tenure'=>'27', 'maturity_tenure'=>'28', 'maturity_amount'=>'5250.00', 'principle_amount'=>'5250.00', 'allow_to_user'=>'0'],
            ['name' => 'Basic', 'amount' => '3500.00', 'monthly_roi_amount'=>'1300',  'tenure'=>'27', 'maturity_tenure'=>'28', 'maturity_amount'=>'5250.00', 'principle_amount'=>'5250.00', 'allow_to_user'=>'0'],
            ['name' => 'Basic', 'amount' => '35000.00', 'monthly_roi_amount'=>'1300',  'tenure'=>'27', 'maturity_tenure'=>'28', 'maturity_amount'=>'52500.00', 'principle_amount'=>'35000.00', 'allow_to_user'=>'1'],
            ['name' => 'Basic', 'amount' => '175000.00','monthly_roi_amount'=>'7000', 'tenure'=>'25', 'maturity_tenure'=>'26', 'maturity_amount'=>'262500.00', 'principle_amount'=>'175000.00', 'allow_to_user'=>'1'],
            ['name' => 'Basic', 'amount' => '350000.00', 'monthly_roi_amount'=>'15000', 'tenure'=>'23.5', 'maturity_tenure'=>'25', 'maturity_amount'=>'525000.00', 'principle_amount'=>'350000.00', 'allow_to_user'=>'1'],
            ['name' => 'Basic', 'amount' => '700000.00','monthly_roi_amount'=>'35000', 'tenure'=>'20', 'maturity_tenure'=>'21', 'maturity_amount'=>'105000.00', 'principle_amount'=>'700000.00', 'allow_to_user'=>'1'],
            ['name' => 'Basic', 'amount' => '350000.00','monthly_roi_amount'=>'6000', 'tenure'=>'60', 'maturity_tenure'=>'60', 'maturity_amount'=>'175000.00', 'principle_amount'=>'250000.00', 'allow_to_user'=>'0'],
            ['name' => 'Basic', 'amount' => '700000.00','monthly_roi_amount'=>'12000', 'tenure'=>'60', 'maturity_tenure'=>'60', 'maturity_amount'=>'350000.00', 'principle_amount'=>'350000.00', 'allow_to_user'=>'0'],

        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
