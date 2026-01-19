<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevelRoiIncome extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userRoiIncome()
    {
        return $this->belongsTo(UserRoiIncome::class);
    }


}
