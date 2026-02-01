<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoPrice extends Model
{
    protected $fillable = [
        'symbol',
        'pair',
        'price',
        'fetched_at',
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'fetched_at' => 'datetime',
    ];
}
