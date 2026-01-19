<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory, SerializeDateTrait;
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class);
    }


    public function city()
    {
        return $this->hasMany(City::class);
    }
}
