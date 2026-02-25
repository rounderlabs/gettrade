<?php

namespace App\Http\Controllers;

use App\Models\KycSubmission;
use App\Models\WithdrawCoin;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KycController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $wallet = $user->withdrawWallets()
            ->whereHas('withdrawCoin', fn($q) => $q->where('is_default', 1))
            ->first();

        return Inertia::render('Account/KycWizard', [
            'kyc' => $user->kyc,
            'withdraw_mode' => $user->withdraw_mode,
            'withdraw_address' => $wallet?->address ?? null,
        ]);
    }

    public function step1(Request $request)
    {
        $request->validate([
            'aadhaar_number' => 'required|digits:12',
            'aadhaar_front' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'aadhaar_back' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = auth()->user();

        $kyc = $user->kyc()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'aadhaar_number' => $request->aadhaar_number,
                'aadhaar_front' => $request->file('aadhaar_front')
                    ->store("kyc/".$user->id, 'private'),
                'aadhaar_back' => $request->file('aadhaar_back')
                    ->store("kyc/".$user->id, 'private'),
                'current_step' => 2,
                'status' => 'pending',
            ]
        );

        return redirect()->route('kyc.index');
    }


    public function step2(Request $request)
    {
        $request->validate([
            'pan_number' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
            'pan_file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = auth()->user();
        $kyc = $user->kyc;

        if (!$kyc) {
            return redirect()->route('kyc.index');
        }

        $kyc->update([
            'pan_number' => strtoupper($request->pan_number),
            'pan_file' => $request->file('pan_file')
                ->store("kyc/".$user->id, 'private'),
            'current_step' => 3,
        ]);

        return redirect()->route('kyc.index');
    }


    public function step3(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'account_number' => 'required|min:9|max:18',
            'cancel_cheque' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = auth()->user();
        $kyc = $user->kyc;

        if (!$kyc) {
            return redirect()->route('kyc.index');
        }

        $kyc->update([
            'bank_name' => $request->bank_name,
            'ifsc_code' => strtoupper($request->ifsc_code),
            'account_number' => $request->account_number,
            'cancel_cheque' => $request->file('cancel_cheque')
                ->store("kyc/".$user->id, 'private'),
            'status' => 'submitted',
        ]);

        KycSubmission::create([
            'user_id' => $user->id,
            'kyc_id' => $kyc->id,
            'aadhaar_number' => $kyc->aadhaar_number,
            'aadhaar_front' => $kyc->aadhaar_front,
            'aadhaar_back' => $kyc->aadhaar_back,
            'pan_number' => $kyc->pan_number,
            'pan_file' => $kyc->pan_file,
            'bank_name' => $kyc->bank_name,
            'ifsc_code' => $kyc->ifsc_code,
            'account_number' => $kyc->account_number,
            'cancel_cheque' => $kyc->cancel_cheque,
            'status' => 'submitted',
        ]);

        return redirect()
            ->route('kyc.status')
            ->with('notification', ['KYC submitted successfully', 'success']);
    }

    public function saveCryptoWallet(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
        ]);

        $user = auth()->user();
        $kyc = $user->kyc;
        $coin = WithdrawCoin::where('is_default', 1)
            ->where('is_active', 1)
            ->firstOrFail();

        $wallet = $user->withdrawWallets()
            ->where('withdraw_coin_id', $coin->id)
            ->first();

        if ($wallet) {
            $wallet->update([
                'address' => $request->address,
            ]);
        } else {
            $user->withdrawWallets()->create([
                'withdraw_coin_id' => $coin->id,
                'address' => $request->address,
            ]);
        }

        KycSubmission::create([
            'user_id' => $user->id,
            'kyc_id' => $kyc->id,
            'aadhaar_number' => $kyc->aadhaar_number,
            'aadhaar_front' => $kyc->aadhaar_front,
            'aadhaar_back' => $kyc->aadhaar_back,
            'pan_number' => $kyc->pan_number,
            'pan_file' => $kyc->pan_file,
            'bank_name' => $kyc->bank_name,
            'ifsc_code' => $kyc->ifsc_code,
            'account_number' => $kyc->account_number,
            'cancel_cheque' => $kyc->cancel_cheque,
            'status' => 'submitted',
        ]);

        return redirect()->route('withdraw.redirect')->with('reload', true);

    }

    public function status()
    {
        $kyc = auth()->user()->kyc;

        abort_unless($kyc, 404);

        return Inertia::render('Account/KycStatus', [
            'kyc' => $kyc,
        ]);
    }

    public function resubmit()
    {
        $kyc = auth()->user()->kyc;

        if ($kyc->status !== 'rejected') {
            abort(403);
        }

        $submission = KycSubmission::where('kyc_id', $kyc->id)
            ->latest()
            ->first();

        $submission->update([
            'status' => 'rejected',
            'rejection_reason' => $kyc->rejection_reason,
        ]);

        $kyc->update([
            'status' => 'pending',   // or 'pending'
            'current_step' => 1,
            'rejection_reason' => null,
        ]);

        return redirect()->route('kyc.index');
    }

    public function history()
    {
        $submissions = auth()->user()
            ->kycSubmissions()
            ->latest()
            ->get();

        return Inertia::render('Account/KycHistory', [
            'submissions' => $submissions,
        ]);
    }

    public function showWizard()
    {

    }
}
