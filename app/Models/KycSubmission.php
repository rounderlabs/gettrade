<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycSubmission extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $fillable = [
        'user_id',
        'kyc_id',
        'aadhaar_number',
        'aadhaar_front',
        'aadhaar_back',
        'pan_number',
        'pan_file',
        'bank_name',
        'ifsc_code',
        'account_number',
        'cancel_cheque',
        'status',
        'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kyc()
    {
        return $this->belongsTo(UserKyc::class);
    }
}
