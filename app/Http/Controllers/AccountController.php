<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfilePicture;
use App\Models\Currency;
use App\Models\WithdrawCoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return Inertia::render('Account/Profile', [
            'profile' => [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'sponsor_id' => $user->tree->sponsor ? auth()->user()->tree->sponsor->email : 'Top ID',
                'sponsor_name' => $user->tree->sponsor ? $user->tree->sponsor->name : 'Top User',
                'joined_on' => $user->created_at,
                'active_at' => $user->active_at ? $user->active_at->format('F j, Y') : null
            ],
            'kyc' => [
                'status' => $user->kyc?->status ?? 'not_submitted',
                'remark' => $user->kyc?->rejection_reason,
            ],
            'currencies' => Currency::where('is_active', true)->get(['code','name']),
        ]);
    }

    public function accountDetails()
    {
        $user = auth()->user();
        return Inertia::render('Account/AccountDetails', [
            'profile' => [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'sponsor_id' => $user->tree->sponsor ? auth()->user()->tree->sponsor->email : 'Top ID',
                'sponsor_name' => $user->tree->sponsor ? $user->tree->sponsor->name : 'Top User',
                'joined_on' => $user->created_at,
                'active_at' => $user->active_at ? $user->active_at->format('F j, Y') : null
            ],
            'kyc' => [
                'status' => $user->kyc?->status ?? 'not_submitted',
                'remark' => $user->kyc?->rejection_reason,
            ]
        ]);
    }

    public function profilePictureForm()
    {
        $user = auth()->user();
        return Inertia::render('Account/ProfilePictureForm', [
            'profile' => [
                'id' => $user->id,
                'full_name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'sponsor_id' => $user->tree->sponsor ? auth()->user()->tree->sponsor->email : null,
                'sponsor_name' => $user->tree->sponsor ? $user->tree->sponsor->name : null,
                'joined_on' => $user->created_at,
                'country' => $user->country,
                'active_at' => $user->active_at ? $user->active_at->format('F j, Y') : null
            ]
        ]);
    }

    public function updateProfilePicture(StoreProfilePicture $request)
    {
        $user = auth()->user();
        $image_path = '';
        if ($request->hasFile('profile_picture')) {
            $image_path = $request->file('profile_picture')->store('user/profile/picture', 'public');
        }
        $updatePicture = $user->update([
            'profile_picture' => $image_path,
        ]);

        if ($updatePicture) {
            return redirect()->route('dashboard')->with('notification', ['Profile Picture Update Successfully', 'success']);
        }
    }

    public function showChangePassword()
    {
        $user = auth()->user();
        return Inertia::render('Account/ChangePassword',['profile'=>$user]);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Your password was not updated, since the provided current password does not match.');
                    }
                }
            ],
            'new_password' => [
                'required', 'min:6', 'confirmed', 'different:current_password'
            ]
        ]);
        $user->fill([
            'password' => $request->new_password,
            'security_answer' => $request->new_password
        ])->save();
        return redirect()->route('dashboard')->with('notification', ['Password Changed Successfully', 'success']);

    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string'],
            'email' => ['required', 'string'],
            'mobile' => ['required', 'digits_between:10,15']
        ]);

        $subscription = auth()->user()->subscriptions;

        if(!is_null($subscription)){
            return redirect()->route('dashboard')->with('notification', ['After Subscription You can not change your profile. Kindly write Mail at @ support@soluminity.com', 'danger']);
        }

        auth()->user()->update([
            'name' => $request->full_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
        ]);
        return redirect()->route('dashboard')->with('notification', ['Profile updated successfully', 'success']);
    }

    public function updateActiveProfile(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string']
        ]);

        auth()->user()->update([
            'name' => $request->full_name
        ]);
        return redirect()->back()->with('notification', ['Profile updated successfully', 'success']);
    }

    public function settingAll()
    {
        $user = auth()->user();
        $subscription = $user->subscriptions;
        return Inertia::render('Account/Setting', [
            'profile' => [
                'id' => $user->id,
                'full_name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'sponsor_id' => $user->tree->sponsor ? auth()->user()->tree->sponsor->email : null,
                'sponsor_name' => $user->tree->sponsor ? $user->tree->sponsor->name : null,
                'joined_on' => $user->created_at,
                'country' => $user->country,
                'active_at' => $user->active_at ? $user->active_at->format('F j, Y') : null
            ],
            'subscription' => $subscription,
        ]);
    }

//    public function withdrawWallet()
//    {
//        $user = auth()->user();
//        $userKyc = $user->kyc;
//        return Inertia::render('Account/WithdrawWalletForm', [
//            'kyc' => $userKyc ? : null,
//        ]);
//    }

    public function withdrawWallet()
    {
        $user = auth()->user();

        $userKyc = $user->kyc;

        $cryptoWallet = $user->withdrawWallets()
            ->whereHas('withdrawCoin', function ($q) {
                $q->where('is_default', 1)
                    ->where('is_active', 1);
            })
            ->first();

        return Inertia::render('Account/WithdrawWalletForm', [
            'kyc' => $userKyc ?: null,
            'withdraw_mode' => $user->withdraw_mode,
            'crypto_wallet' => $cryptoWallet,
        ]);
    }

    public function updateCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|exists:currencies,code',
        ]);
        $user = auth()->user();
        $currencyUpdate = $user->update([
            'preferred_currency' => $request->currency,
        ]);
        if ($currencyUpdate){
            return back()->with('notification',['Currency updated', 'success']);
        }else{
            return back()->with('notification',['Currency not updated', 'danger']);
        }


    }

    public function updateWithdrawMode(Request $request)
    {
        $request->validate([
            'withdraw_mode' => 'required|in:INR,CRYPTO'
        ]);

        $user = auth()->user();

        $user->update([
            'withdraw_mode' => $request->withdraw_mode
        ]);

        return back();
    }

}
