<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRankRoiIncomes extends Model
{
    use HasFactory;

    protected $table = 'user_rank_roi_incomes';

    protected $fillable = [
        'user_id',
        'user_roi_income_id',
        'rank',
        'income_percent',
        'income',
    ];

    /* ======================
       RELATIONS
    ====================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roiIncome()
    {
        return $this->belongsTo(UserRoiIncome::class, 'user_roi_income_id');
    }
    public function userRoiIncome()
    {
        return $this->belongsTo(
            UserRoiIncome::class,
            'user_roi_income_id'
        );
    }
}
