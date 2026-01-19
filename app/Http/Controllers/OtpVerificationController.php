<?php

namespace App\Http\Controllers;

use App\Methods\OtpMethod;
use App\Models\User;
use App\Notifications\OtpNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OtpVerificationController extends Controller
{
    public function Index()
    {
        return Inertia('Auth/OtpNotice');
    }

    public function resendOtp()
    {
        $user = User::find(Auth::user()->id);
        $otpModel = OtpMethod::init()->create()->save($user, 30);
        $user->notify(new OtpNotification($otpModel->code));
        return redirect()->route('otp.notice')->with('notification', ['OTP sent to your email address', 'success']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6',
                function ($attribute, $value, $fail) {
                    $otpModel = Auth::user()->otps()->where('code', $value)->where('is_used', false)->orderByDesc('id')->first();
                    if (is_null($otpModel)) {
                        $fail("Invalid OTP");
                    } elseif (now()->unix() > $otpModel->expire_at->unix()) {
                        $fail("OTP has expired. Please to resend again.");
                    }
                }
            ]
        ]);

        $otpModel = Auth::user()->otps()->where('code', $request->code)->where('is_used', false)->orderByDesc('id')->first();
        $otpModel->update([
            'is_used' => true
        ]);

        $user = User::find(Auth::user()->id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));

//            $userSponsor = UserSponsor::where('user_id', $user->id)->first();
//            if (!is_null($userSponsor)) {
//                $sponsor = User::where('id', $userSponsor->sponsor_id)->first();
//                PostRegisterationJob::dispatch($user, $sponsor, $userSponsor->position)->delay(now()->addSecond());
//            }
        }

        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');

    }
}

