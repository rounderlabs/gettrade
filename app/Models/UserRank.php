<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRank extends Model
{
    protected $fillable = [
        'user_id',
        'rank',
        'achieved_at',
    ];

    protected $casts = [
        'achieved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
