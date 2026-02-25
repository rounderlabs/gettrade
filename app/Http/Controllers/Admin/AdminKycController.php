<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserKyc;
use App\Models\KycSubmission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminKycController extends Controller
{
    public function index(Request $request)
    {
        $kycs = UserKyc::with('user')
//            ->where('status', 'submitted')
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
        if ($kyc->status !== 'submitted') {
            abort(403, 'KYC already processed');
        }

        $kyc->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        // ✅ Update latest submission
        KycSubmission::where('kyc_id', $kyc->id)
            ->latest()
            ->first()
            ?->update([
                'status' => 'approved',
            ]);

        $kyc->user->update([
            'kyc_verified' => true,
        ]);

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

        return Storage::disk('private')->download($path);
    }
}
