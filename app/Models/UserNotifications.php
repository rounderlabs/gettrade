<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class UserNotifications extends Model
{
    use HasFactory, SerializesModels;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
