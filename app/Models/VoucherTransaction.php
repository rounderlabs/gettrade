<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherTransaction extends Model implements IDepositTransactionMorph
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function depositTransaction()
    {
        return $this->morphOne(DepositTransaction::class, 'deposit_transactionable');
    }
}
