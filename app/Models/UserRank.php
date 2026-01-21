<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRank extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    protected $casts = [
        'achieved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
