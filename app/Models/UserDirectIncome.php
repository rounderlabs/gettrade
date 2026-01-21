<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDirectIncome extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
