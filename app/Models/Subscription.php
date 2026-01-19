<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'end_date' => 'date:Y-m-d'
    ];

    protected $appends = [
        'subscription_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userUsdWalletTransaction()
    {
        return $this->belongsTo(UserUsdWalletTransaction::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function getSubscriptionDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

    public function userRoiIncomes()
    {
        return $this->hasMany(UserRoiIncome::class);
    }

    public function validator()
    {
        return $this->hasOne(Validator::class);
    }


}
