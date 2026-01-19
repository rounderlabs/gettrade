<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevelIncomeStat extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userLevelIncome()
    {
        return $this->belongsTo(UserLevelIncome::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_id'); // The referrer (who generated the income)
    }


}
