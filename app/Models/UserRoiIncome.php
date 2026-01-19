<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoiIncome extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }




    public function userLevelRoiIncomes()
    {
        return $this->hasMany(UserLevelRoiIncome::class);
    }
}
