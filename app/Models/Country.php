<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory,SerializeDateTrait;

    public function user()
    {
        return $this->hasOne(User::class);
    }


}
