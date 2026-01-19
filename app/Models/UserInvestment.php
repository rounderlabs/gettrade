<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvestment extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function levelRoiIncomes()
    {
        return $this->hasMany(UserLevelRoiIncome::class);
    }

    public function incomeSetting()
    {
        return $this->hasOne(UserIncomeSetting::class, 'user_investment_id');
    }

    public function inventory()
    {
        return $this->hasOne(InvestmentInventories::class, 'user_investment_id');
    }

    public function payments()
    {
        return $this->hasMany(UserInvestmentPayment::class, 'user_investment_id');
    }

}
