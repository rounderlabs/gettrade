<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function isUserExist(Request $request)
    {
        $request->validate([
            'sponsor_id' => ['required', 'numeric', 'exists:users,id']
        ]);

        return response()->json(['sponsor_name' => User::find($request->sponsor_id)->name]);
    }

    public function isUserExistByRefCode(Request $request)
    {
        $request->validate([
            'referral' => ['required', 'string', 'exists:users,ref_code',
//                function ($attribute, $value, $fail) {
//                    $subscriptionCount = User::withCount('subscriptions')->where('ref_code', $value)->first();
//                    if ($subscriptionCount->subscriptions_count < 1) {
//                        $fail('The ' . $attribute . ' is not active!.');
//                    }
//                },
            ]
        ]);

        return response()->json(['sponsor_name' => User::whereRefCode($request->referral)->first()->name]);
    }

    public function isUsernameExist(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'exists:users,username']
        ]);

        return response()->json(['username' => 'Already Taken']);
    }



    public function getUserApi(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'api_key' => 'required|string|exists:apikeys,api_key',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid Input', 'result' => $validator->messages()], 433);
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth()->user();
            return response()->json([
                'status' => 'success',
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'username' => $user->username,
            ]);
        } else {
            return response()->json(['error' => 'Invalid login details']);
        }
    }
}
