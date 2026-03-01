<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserKyc;
use App\Models\KycSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class AdminKycController extends Controller
{
    public function index(Request $request)
    {
        $kycs = UserKyc::with('user')
            ->latest()
            ->paginate(10);

        if ($request->expectsJson()) {
            return response()->json($kycs);
        }

        return Inertia::render('Admin/Kyc/Index', [
            'kycs' => $kycs,
        ]);
    }

    public function show(UserKyc $kyc)
    {
        $latestSubmission = KycSubmission::where('kyc_id', $kyc->id)
            ->latest()
            ->first();
        if (is_null($latestSubmission)) {
            KycSubmission::create([
                'user_id' => $kyc->user_id,
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

        }

        return Inertia::render('Admin/Kyc/Show', [
            'kyc' => $kyc->load('user'),
            'latestSubmission' => $latestSubmission,
            'history' => KycSubmission::where('kyc_id', $kyc->id)
                ->latest()
                ->get(),
        ]);
    }

    public function approve(UserKyc $kyc)
    {
        DB::transaction(function () use ($kyc) {

            $updated = UserKyc::where('id', $kyc->id)
                ->where('status', 'submitted')
                ->update([
                    'status' => 'approved',
                    'rejection_reason' => null,
                ]);

            if (!$updated) {
                abort(409, 'KYC already processed');
            }

            $latest = KycSubmission::where('kyc_id', $kyc->id)
                ->latest()
                ->lockForUpdate()
                ->first();

            $latest?->update([
                'status' => 'approved',
            ]);

            $kyc->user()->update([
                'kyc_verified' => true,
            ]);
        });

        return redirect()
            ->route('admin.kyc.index')
            ->with('success', 'KYC approved successfully');
    }

    public function reject(Request $request, UserKyc $kyc)
    {
        if ($kyc->status !== 'submitted') {
            abort(403, 'KYC already processed');
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:5',
        ]);

        $kyc->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // ✅ Update latest submission
        KycSubmission::where('kyc_id', $kyc->id)
            ->latest()
            ->first()
            ?->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

        return redirect()
            ->route('admin.kyc.index')
            ->with('success', 'KYC rejected successfully');
    }

//    public function download(KycSubmission $submission, $field)
//    {
//        $allowedFields = [
//            'aadhaar_front',
//            'aadhaar_back',
//            'pan_file',
//            'cancel_cheque',
//        ];
//
//        abort_unless(in_array($field, $allowedFields), 403);
//
//        $path = $submission->$field;
//
//        abort_unless($path && Storage::disk('private')->exists($path), 404);
//
//        return Storage::disk('private')->download($path);
//    }

    public function download(KycSubmission $submission, $field)
    {
        $allowedFields = [
            'aadhaar_front',
            'aadhaar_back',
            'pan_file',
            'cancel_cheque',
        ];

        abort_unless(in_array($field, $allowedFields), 403);

        $path = $submission->$field;

        abort_unless($path && Storage::disk('private')->exists($path), 404);

        return response()->file(
            Storage::disk('private')->path($path)
        );
    }
}
