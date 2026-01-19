<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];


    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
