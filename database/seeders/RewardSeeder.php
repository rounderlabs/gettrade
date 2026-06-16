<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing rewards to ensure a clean slate
        Reward::truncate();

        $rewards = [
            [
                'rank_name' => 'Associate',
                'matching_leg_business' => 250000.00,
                'reward_amount' => 15000.00,
                'salary_amount' => 0.00,
                'salary_tenure' => 0,
                'reward_text' => 'Associate Reward Unlocked',
            ],
            [
                'rank_name' => 'Star Associate',
                'matching_leg_business' => 500000.00,
                'reward_amount' => 30000.00,
                'salary_amount' => 0.00,
                'salary_tenure' => 0,
                'reward_text' => 'Star Associate Reward Unlocked',
            ],
            [
                'rank_name' => 'Bronze',
                'matching_leg_business' => 1000000.00,
                'reward_amount' => 50000.00,
                'salary_amount' => 5000.00,
                'salary_tenure' => 12,
                'reward_text' => 'Bronze Rank Achieved',
            ],
            [
                'rank_name' => 'Silver',
                'matching_leg_business' => 2000000.00,
                'reward_amount' => 100000.00,
                'salary_amount' => 10000.00,
                'salary_tenure' => 12,
                'reward_text' => 'Silver Rank Achieved',
            ],
            [
                'rank_name' => 'Gold',
                'matching_leg_business' => 5000000.00,
                'reward_amount' => 250000.00,
                'salary_amount' => 25000.00,
                'salary_tenure' => 12,
                'reward_text' => 'Gold Rank Achieved',
            ],
            [
                'rank_name' => 'Platinum',
                'matching_leg_business' => 10000000.00,
                'reward_amount' => 500000.00,
                'salary_amount' => 50000.00,
                'salary_tenure' => 24,
                'reward_text' => 'Platinum Rank Achieved',
            ],
            [
                'rank_name' => 'Ruby',
                'matching_leg_business' => 20000000.00,
                'reward_amount' => 1000000.00,
                'salary_amount' => 100000.00,
                'salary_tenure' => 24,
                'reward_text' => 'Ruby Rank Achieved',
            ],
            [
                'rank_name' => 'Emerald',
                'matching_leg_business' => 50000000.00,
                'reward_amount' => 2500000.00,
                'salary_amount' => 250000.00,
                'salary_tenure' => 24,
                'reward_text' => 'Emerald Rank Achieved',
            ],
            [
                'rank_name' => 'Sapphire',
                'matching_leg_business' => 100000000.00,
                'reward_amount' => 5000000.00,
                'salary_amount' => 500000.00,
                'salary_tenure' => 36,
                'reward_text' => 'Sapphire Rank Achieved',
            ],
            [
                'rank_name' => 'Diamond',
                'matching_leg_business' => 200000000.00,
                'reward_amount' => 10000000.00,
                'salary_amount' => 1000000.00,
                'salary_tenure' => 36,
                'reward_text' => 'Diamond Rank Achieved',
            ],
            [
                'rank_name' => 'Double Diamond',
                'matching_leg_business' => 500000000.00,
                'reward_amount' => 25000000.00,
                'salary_amount' => 2500000.00,
                'salary_tenure' => 36,
                'reward_text' => 'Double Diamond Rank Achieved',
            ],
            [
                'rank_name' => 'Triple Diamond',
                'matching_leg_business' => 1000000000.00,
                'reward_amount' => 50000000.00,
                'salary_amount' => 5000000.00,
                'salary_tenure' => 48,
                'reward_text' => 'Triple Diamond Rank Achieved',
            ],
            [
                'rank_name' => 'Crown Diamond',
                'matching_leg_business' => 2000000000.00,
                'reward_amount' => 100000000.00,
                'salary_amount' => 10000000.00,
                'salary_tenure' => 48,
                'reward_text' => 'Crown Diamond Rank Achieved',
            ],
            [
                'rank_name' => 'Global Ambassador',
                'matching_leg_business' => 5000000000.00,
                'reward_amount' => 250000000.00,
                'salary_amount' => 25000000.00,
                'salary_tenure' => 60,
                'reward_text' => 'Global Ambassador Rank Unlocked',
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
