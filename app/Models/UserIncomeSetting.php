<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIncomeSetting extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];


}
