<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawCoin extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    use HasFactory, SerializeDateTrait;


    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function withdrawWallets()
    {
        return $this->hasMany(WithdrawWallet::class);
    }
}
