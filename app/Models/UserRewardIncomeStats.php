<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRewardIncomeStats extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Reward type (like “Car Bonus”, “Laptop Reward”, etc.)
    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_id');
    }

    // Reward income closing session
    public function rewardIncomeClosing()
    {
        return $this->belongsTo(RewardIncomeClosing::class, 'reward_income_closing_id');
    }
}
