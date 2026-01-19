<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    use HasFactory, SerializeDateTrait;

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
        return $this->belongsTo(User::class, 'sponsor_id', 'id');
    }

    public function scopeWherePosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function childrens()
    {
        return $this->hasMany(Tree::class, 'sponsor_id', 'user_id');
    }
}
