<?php

namespace App\Http\Controllers;

use App\Models\KycSubmission;
use App\Models\UserKyc;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class KycController extends Controller
{
    public function index()
    {
        $kyc = UserKyc::firstOrCreate([
            'user_id' => auth()->id(),
        ]);
        if (in_array($kyc->status, ['submitted', 'approved'])) {
            return redirect()->route('kyc.status');
        }
        return Inertia::render('Account/KycWizard', [
            'kyc' => $kyc,
        ]);
    }

    /* STEP 1 – AADHAAR */
    public function step1(Request $request)
    {
        $request->validate([
            'aadhaar_number' => 'required|digits:12',
            'aadhaar_front' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'aadhaar_back'  => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $kyc = auth()->user()->kyc;

        $path = "kyc/" . auth()->id();

        $kyc->update([
            'aadhaar_number' => $request->aadhaar_number,
            'aadhaar_front' => $request->file('aadhaar_front')->store($path, 'private'),
            'aadhaar_back'  => $request->file('aadhaar_back')->store($path, 'private'),
            'current_step' => 2,
        ]);

        return redirect()->route('kyc.index');
    }

    /* STEP 2 – PAN */
    public function step2(Request $request)
    {
        $request->validate([
            'pan_number' => [
                'required',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
            ],
            'pan_file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $kyc = auth()->user()->kyc;

        $kyc->update([
            'pan_number' => $request->pan_number,
            'pan_file' => $request->file('pan_file')
                ->store("kyc/" . auth()->id(), 'private'),
            'current_step' => 3,
        ]);

        return redirect()->route('kyc.index');
    }

    /* STEP 3 – BANK */
    public function step3(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'account_number' => 'required|min:9|max:18',
            'cancel_cheque' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $kyc = auth()->user()->kyc;

        $kyc->update([
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'account_number' => $request->account_number,
            'cancel_cheque' => $request->file('cancel_cheque')
                ->store("kyc/" . auth()->id(), 'private'),
            'current_step' => 3,
            'status' => 'submitted',
        ]);

        KycSubmission::create([
            'user_id' => auth()->id(),
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

        return redirect()->route('kyc.index')->with('notification', ['KYC submitted successfully', 'success']);

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
}
