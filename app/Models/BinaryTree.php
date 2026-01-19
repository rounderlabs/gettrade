<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinaryTree extends Model
{
    use HasFactory, SerializeDateTrait;

    const POSITIONS = [
        'LEFT' => 'LEFT',
        'RIGHT' => 'RIGHT'
    ];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id', 'id');
    }

    public function setPositionAttribute($value)
    {
        $this->attributes['position'] = strtoupper($value);
    }

    public function scopeLastChildNode($query, $userId, $position)
    {
        return $query->where('parent_id', $userId)->where('position', $position)->orderBy('id', 'desc')->take(1);
    }

    public function childrens()
    {
        return $this->hasMany(BinaryTree::class, 'parent_id', 'user_id');
    }


}
