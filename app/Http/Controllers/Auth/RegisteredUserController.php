<?php

namespace App\Http\Controllers\Auth;

use App\Events\Registered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        if (is_null($request->referral)) {
            $sponsorUser = User::find(1);
        } else {
            $sponsorUser = User::whereRefCode($request->referral)->first();
        }

        $ref_code = generateRefCode();
        $user = User::create([
            'name' => $request->full_name,
            'ref_code' => $ref_code,
            'username' => $ref_code,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => $request->password,
            'security_answer' => $request->password,
            'mobile' => $request->mobile,
            'country_id' => 100,
            'sponsor_id' => (int)$sponsorUser->id,
            'position' => null,
        ]);

        $lastTreeChild = $sponsorUser->tree->childrens()->orderBy('position', 'desc')->first();

        $user->tree()->create([
            'sponsor_id' => $sponsorUser->id,
            'position' => is_null($lastTreeChild) ? 1 : (int)$lastTreeChild->position + 1
        ]);
        $user->team()->create();
        event(new Registered($user));

        try {
//            $otpModel = OtpMethod::init()->create()->save($user, 30);
//            $user->notify(new OtpNotification($otpModel->code));
            if ((int)siteSetting('welcome_email_enabled') === 1) {
                $user->notify(new WelcomeNotification($user));
            }
        } catch (\Throwable $e) {
            logger()->error('Welcome email failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
        Auth::login($user);
        return redirect(RouteServiceProvider::HOME);
    }

    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'mobile' => ['required', 'digits_between:10,11'],

            //           'referral' => ['required', 'string', 'exists:users,ref_code',
//                function ($attribute, $value, $fail) {
//                    $subscriptionCount = User::withCount('subscriptions')->where('ref_code', $value)->first();
//                    if ($subscriptionCount->subscriptions_count < 1) {
//                        $fail('The ' . $attribute . ' is not active!.');
//                    }
//                },
            //           ],
        ]);
    }

    /**
     * Display the registration view.
     *
     */
    public function create(?string $ref_code = null)
    {
        $referred_by = null;

        if ($ref_code) {
            $validator = Validator::make(['ref_code' => $ref_code], [
                'ref_code' => ['exists:users,ref_code'],
            ]);

            if (!$validator->fails()) {
                $referred_by = $ref_code;
            }
        }

        return Inertia::render('Auth/Register', [
            'referred_by' => $referred_by,
        ]);
    }


}
