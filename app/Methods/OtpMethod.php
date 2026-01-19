<?php


namespace App\Methods;


use App\Models\Otp;
use App\Models\User;
use Exception;

class OtpMethod
{
    private string $otp;

    public function __construct()
    {
    }

    public static function init(): self
    {
        return new static();
    }

    public function create(): self
    {
        $this->otp = (string)rand(100000, 999999);
        return $this;
    }

    public function get(): string
    {
        return $this->otp;
    }

    public function save(User $user, int $expiryMinutes = 10): ?Otp
    {
        if (is_null($this->otp)) {
            throw new Exception('OTP can not be null');
        }

        return $user->otps()->create([
            'code' => $this->otp,
            'expire_at' => now()->addMinutes($expiryMinutes),
            'is_used' => false
        ]);

    }

}
