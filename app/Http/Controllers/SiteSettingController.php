<?php

namespace App\Http\Controllers;

use App\Models\AdminFundWallet;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SiteSettingController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Setting/SiteSettings', [
            'settings' => SiteSetting::pluck('value', 'key'),
            'groups' => [
                'general' => 'General',
                'branding' => 'Branding',
                'commission' => 'Commission',
                'email' => 'Email',
                'deposit'    => 'Deposit Settings',
            ],
        ]);
    }

    public function updateGeneral(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required|string|max:100',
            'site_tagline' => 'nullable|string|max:255',
            'referral_prefix' => [
                'nullable',
                'string',
                'min:2',
                'max:5',
                'regex:/^[A-Z]+$/'
            ],
        ]);

        SiteSetting::set(
            'referral_prefix',
            strtoupper($request->referral_prefix ?? '')
        );

        cache()->forget('site_setting_referral_prefix');

        foreach ($data as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return back()->with('notification', ['Settings updated successfully','success']);
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'logo_desktop' => 'nullable|image|max:2048',
            'logo_mobile' => 'nullable|image|max:2048',
        ]);

        foreach (['logo_desktop', 'logo_mobile'] as $key) {
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('logos', 'public');
                SiteSetting::set($key, '/storage/' . $path);
            }
        }

        return back()->with('notification', ['Settings updated successfully','success']);
    }

    public function updateCommission(Request $request)
    {
        $data = $request->validate([
            'direct_percent' => 'required|numeric|min:0|max:100',
            'level_percentages' => 'required|array|min:1',
            'level_percentages.*.level' => 'required|integer|min:1',
            'level_percentages.*.percent' => 'required|numeric|min:0|max:100',
        ]);

        $total = collect($data['level_percentages'])->sum('percent');
        if ($total > 100) {
            return back()->withErrors([
                'level_percentages' => 'Total level commission cannot exceed 100%',
            ]);
        }

        SiteSetting::set('direct_percent', $data['direct_percent']);
        SiteSetting::set('level_percentages', $data['level_percentages']);

        return back()->with('notification', ['Settings updated successfully','success']);
    }



    public function update(Request $request)
    {
        $rules = [
            'site_name' => 'required|string|max:100',
            'site_tagline' => 'nullable|string|max:255',

            'direct_percent' => 'required|numeric|min:0|max:100',

            'level_percentages' => 'required|array',
            'level_percentages.*' => 'numeric|min:0|max:100',

            'site_status' => 'required|in:live,maintenance',

            'logo_desktop' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'logo_mobile' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if (in_array($key, ['logo_desktop', 'logo_mobile']) && $request->hasFile($key)) {
                $path = $request->file($key)->store('logos', 'public');
                SiteSetting::set($key, '/storage/' . $path);
            } else {
                SiteSetting::set($key, $value);
            }
        }

        return back()->with('notification', ['Settings updated successfully','success']);
    }


    public function updateEmail(Request $request)
    {
        $data = $request->validate([
            'email_from_name' => 'required|string',
            'email_from_address' => 'required|email',
            'email_footer_text' => 'nullable|string',
            'welcome_email_subject' => 'required|string',
            'welcome_email_enabled' => 'boolean',
        ]);

        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Email settings updated');
    }

    public function previewWelcomeEmail()
    {
        $fakeUser = (object)[
            'name' => 'John Doe',
            'username' => 'TSK12345',
            'ref_code' => 'TSK99999',
        ];

        return view('emails.welcome', [
            'user' => $fakeUser,
        ]);
    }


    public function updateDeposit(Request $request)
    {
        $request->validate([
            'deposit_wallet_address' => 'required|string',
        ]);

        $deposit = AdminFundWallet::find(1);
        $deposit->update([
            'coin'=>'bep20_usdt',
            'address'=>$request->deposit_wallet_address,
            'can_deposit'=>1,
            'is_active'=>1,
        ]);

        return back()->with('notification', ['Deposit settings updated', 'success']);
    }

    public function updateWithdrawal(Request $request)
    {
        $request->validate([
            'withdrawal_wallet_address' => 'required|string',
            'withdrawal_network' => 'required|string',
            'min_withdraw_amount' => 'required|numeric|min:0',
            'withdraw_fee_percent' => 'required|numeric|min:0|max:100',
        ]);

        setting([
            'withdrawal_wallet_address' => $request->withdrawal_wallet_address,
            'withdrawal_network' => $request->withdrawal_network,
            'min_withdraw_amount' => $request->min_withdraw_amount,
            'withdraw_fee_percent' => $request->withdraw_fee_percent,
        ])->save();

        return back()->with('success', 'Withdrawal settings updated');
    }
}
