<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKyc extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(KycSubmission::class, 'kyc_id');
    }
}
