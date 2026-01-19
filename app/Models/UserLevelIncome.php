<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevelIncome extends Model
{
    use HasFactory,SerializeDateTrait;

    protected $guarded=[];

    protected $casts = [
        'income_date' => 'datetime:Y-m-d'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userLevelIncomeStats()
    {
        return $this->hasMany(UserLevelIncomeStat::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_id'); // or 'from_user_id', depending on your schema
    }
}
