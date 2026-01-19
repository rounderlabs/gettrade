<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalHistory extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function getTxnDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }


}
