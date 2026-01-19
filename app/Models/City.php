<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory, SerializeDateTrait;

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
