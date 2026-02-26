<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositTransaction extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $appends = ['txn_date', 'price_in_usd'];

    protected $guarded = [];

    protected $casts = [
        'txn_time' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function depositTransactionable()
    {
        return $this->morphTo();
    }

    public function getTxnDateAttribute()
    {
        return $this->txn_time->format('F j, Y');
    }

    public function getPriceInUsdAttribute()
    {
        return $this->crypto_price;
    }

}
