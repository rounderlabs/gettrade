<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserFundAddRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminFundRequestController extends Controller
{
    /**
     * List fund requests
     */
    public function index()
    {
        $requests = UserFundAddRequest::with('user')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/FundRequests/Index', [
            'requests' => $requests,
        ]);
    }

    /**
     * Accept fund request
     */
    public function accept(UserFundAddRequest $request)
    {
        abort_if($request->status !== 'pending', 403);

        DB::transaction(function () use ($request) {

            // 👉 Optional: credit wallet here
            // $request->user->wallet()->increment('balance', $request->amount);

            $request->update([
                'status' => 'accepted',
                'comment' => null,
            ]);
        });

        return back()->with('success', 'Fund request accepted successfully.');
    }

    /**
     * Reject fund request
     */
    public function reject(Request $httpRequest, UserFundAddRequest $request)
    {
        abort_if($request->status !== 'pending', 403);

        $httpRequest->validate([
            'comment' => 'required|string|max:500',
        ]);

        $request->update([
            'status' => 'rejected',
            'comment' => $httpRequest->comment,
        ]);

        return back()->with('success', 'Fund request rejected.');
    }

    /**
     * Secure payment proof download (PRIVATE DISK)
     */
    public function download(UserFundAddRequest $request)
    {
        abort_unless(auth('admin')->check(), 403);

        $path = $request->payment_proof;

        // hard fail if path is empty
        abort_unless($path, 404);

        // verify file exists on private disk
        abort_unless(
            Storage::disk('private')->exists($path),
            404
        );

        return Storage::disk('private')->download($path);
    }
}

