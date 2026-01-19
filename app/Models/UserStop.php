<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStop extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $guarded = [];

    protected $casts = [
        'is_blocked' => 'boolean',
        'level' => 'boolean',
        'roi' => 'boolean',
        'roi_on_roi' => 'boolean',
        'maturity_level' => 'boolean',
        'bonanza' => 'boolean',
        'reward' => 'boolean',
        'withdrawal' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
