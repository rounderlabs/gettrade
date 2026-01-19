<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUsdWalletTransaction extends Model
{
    use HasFactory, SerializeDateTrait;

    const TXN_TYPE = [
        'DEBIT' => 'debit',
        'CREDIT' => 'credit'
    ];
    const TXN_SUMMARY = [
        'ACTIVE PLAN' => 'Plan Activation',
        'AUTO_ACTIVATION_PLAN' => 'Auto Plan Activation',
        'REWARD TRANSFERRED' => 'Reward Converted into USD',
        'TOKEN_BUY' => 'Token Purchased',
        'VALIDATOR_LINK' => 'VALIDATOR_LINK',
        'VALIDATOR_PREBOOKING' => 'Validator Pre Booking',
        'VALIDATOR_PENDING_PAYMENT' => 'Validator Pending Payment'

    ];

    const TXN_STATUS = [
        'SUCCESS' => 'success',
        'PENDING' => 'pending',
        'FAIL' => 'fail'
    ];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userUsdWallet()
    {
        return $this->belongsTo(UserUsdWallet::class);
    }

    public function userIncomeWallet()
    {
        return $this->belongsTo(UserIncomeWallet::class);
    }

    public function userTokenWallet()
    {
        return $this->belongsTo(userTokenWallet::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
}
