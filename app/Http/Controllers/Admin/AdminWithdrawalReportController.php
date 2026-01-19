<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WithdrawalExport;

class AdminWithdrawalReportController extends Controller
{
    public function index(Request $request)
    {
        $query = WithdrawalHistory::with('user');

        // Filters
        if ($request->filled('username')) {
            $query->whereHas('user', fn ($q) =>
            $q->where('username', 'like', "%{$request->username}%")
            );
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Totals (clone query to avoid pagination impact)
        $totals = (clone $query)->selectRaw("
            SUM(amount) as total_amount,
            SUM(fees) as total_fees,
            SUM(receivable_amount) as total_receivable
        ")->first();

        return Inertia::render('Admin/Reports/WithdrawalReport', [
            'withdrawals' => $query->latest()->paginate(20)->withQueryString(),
            'filters' => $request->only([
                'username', 'type', 'status', 'from_date', 'to_date'
            ]),
            'totals' => $totals,
        ]);
    }

    public function export(Request $request)
    {
        $query = WithdrawalHistory::with('user');

        // Filters
        if ($request->filled('username')) {
            $query->whereHas('user', fn ($q) =>
            $q->where('username', 'like', "%{$request->username}%")
            );
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // CSV Header
            fputcsv($handle, [
                'User ID',
                'Name',
                'Txn ID',
                'Amount',
                'Fees',
                'Receivable',
                'Type',
                'Bank Name',
                'IFSC Code',
                'Account Number',
                'UPI ID',
                'UPI Number',
                'Status',
                'Date',
            ]);

            foreach ($query->latest()->cursor() as $row) {
                fputcsv($handle, [
                    $row->user->username,
                    $row->user->name,
                    $row->txn_id,
                    $row->amount,
                    $row->fees,
                    $row->receivable_amount,
                    ucfirst($row->type),
                    $row->bank_name,
                    $row->bank_ifsc,
                    $row->bank_account_no,
                    $row->upi_id,
                    $row->upi_number,
                    ucfirst($row->status),
                    $row->txn_date,
                ]);
            }

            fclose($handle);
        }, 'withdrawal-report.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function updateStatus(Request $request, WithdrawalHistory $withdrawal)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,success,failed',
            'txn_id' => 'required_if:status,success|string|max:255',
        ]);

        // Prevent changing final state
        if (in_array($withdrawal->status, ['success', 'failed'])) {
            abort(403, 'Final status cannot be changed');
        }

        $data = [
            'status' => $request->status,
        ];

        // If success, txn_id is mandatory
        if ($request->status === 'success') {
            $data['txn_id'] = $request->txn_id;
        }

        $withdrawal->update($data);

        return back()->with('notification', [
            'Withdrawal status updated successfully',
            'success',
        ]);
    }



}


