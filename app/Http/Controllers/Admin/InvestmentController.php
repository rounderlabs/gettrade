<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentInventories;
use App\Models\UserIncomeSetting;
use App\Models\UserInvestment;
use App\Models\UserInvestmentPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvestmentController extends Controller
{
    /**
     * Display the investment report page.
     */
    public function index()
    {
        return Inertia::render('Admin/Subscription/Investment');
    }

    /**
     * Fetch investment records with filters.
     */
    public function getInvestments(
        $user_id = 'noId',
        $from_date = 'noDate',
        $to_date = 'noDate',
        $guaranty_type = 'all',
        $investment_type = 'all'
    ) {
        $query = UserInvestment::with('user')
            ->where('amount', '>', 0)
            ->orderByDesc('id')
            ->withCasts(['created_at' => 'datetime:Y-m-d']);

        // 🔹 Filter by user ID / username
        if ($user_id !== 'noId') {
            $query->whereHas('user', function ($q) use ($user_id) {
                $q->where('id', $user_id)
                    ->orWhere('username', 'like', "%{$user_id}%");
            });
        }

        // 🔹 Filter by guaranty type
        if ($guaranty_type !== 'all') {
            $query->where('guaranty_type', $guaranty_type);
        }

        // 🔹 Filter by investment type
        if ($investment_type !== 'all') {
            $query->where('investment_type', $investment_type);
        }

        // 🔹 Filter by date range
        if ($from_date !== 'noDate' && $to_date !== 'noDate') {
            $query->whereBetween('created_at', [
                Carbon::parse($from_date)->startOfDay(),
                Carbon::parse($to_date)->endOfDay(),
            ]);
        } elseif ($from_date !== 'noDate') {
            $query->whereDate('created_at', '>=', $from_date);
        } elseif ($to_date !== 'noDate') {
            $query->whereDate('created_at', '<=', $to_date);
        }

        return response()->json($query->paginate(10));
    }

    /**
     * Show investment details page.
     */
    public function show($id)
    {
        $investment = UserInvestment::with(['user','user.userIncomeStat', 'incomeSetting'])->findOrFail($id);

        return Inertia::render('Admin/Subscription/InvestmentDetailsByID', [
            'investment' => $investment,
            'setting' => $investment->incomeSetting,
        ]);
    }

    /**
     * Save or update investment settings.
     */
    public function saveSetting(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'user_investment_id' => 'required|exists:user_investments,id',
            'guaranty_type' => 'required|string',
            'monthly_return_percent' => 'required|numeric|min:0',
            'passive_return_x' => 'required|numeric|min:0',
            'working_return_x' => 'required|numeric|min:0',
            'document' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'details' => 'nullable|string',

            // Conditional fields (optional)
            'project_name' => 'nullable|string|max:255',
            'project_location' => 'nullable|string|max:255',
            'plot_number' => 'nullable|string|max:100',
            'acquisition_type' => 'nullable|in:Registry,Agreement',
            'payment_mode' => 'nullable|in:Cash,NEFT,RTGS,UPI,IMPS,Cheque',

            // Payment Mode Specific
            'amount' => 'nullable|numeric|min:0',
            'date_of_payment' => 'nullable|date',
            'transaction_id' => 'nullable|string|max:255',
            'txn_date' => 'nullable|date',
            'bank_name' => 'nullable|string|max:255',
            'cheque_number' => 'nullable|string|max:255',
            'payment_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        // ✅ Optional File Upload
        $path = null;
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('income-documents', 'public');
        }

        // ✅ Save or Update
        $setting = UserIncomeSetting::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'user_investment_id' => $validated['user_investment_id'],
            ],
            [
                'guaranty_type' => $validated['guaranty_type'],
                'monthly_return_percent' => $validated['monthly_return_percent'],
                'passive_return_x' => $validated['passive_return_x'],
                'working_return_x' => $validated['working_return_x'],
                'document_path' => $path ?? null,
                'details' => $validated['details'] ?? null,

                // 🔹 Guaranty specific fields
                'project_name' => $validated['project_name'] ?? null,
                'project_location' => $validated['project_location'] ?? null,
                'plot_number' => $validated['plot_number'] ?? null,
                'acquisition_type' => $validated['acquisition_type'] ?? null,

                // 🔹 Payment details
                'payment_mode' => $validated['payment_mode'] ?? null,
                'amount' => $validated['amount'] ?? null,
                'date_of_payment' => $validated['date_of_payment'] ?? null,
                'transaction_id' => $validated['transaction_id'] ?? null,
                'txn_date' => $validated['txn_date'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'cheque_number' => $validated['cheque_number'] ?? null,
                'payment_percent' => $validated['payment_percent'] ?? null,

                'updated_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Investment setting saved successfully!',
            'data' => $setting,
        ]);
    }


    public function saveInvestmentInventory(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'user_investment_id' => 'required|exists:user_investments,id',
            'guaranty_type' => 'required|in:land,flat,mou,cheque',
            'project_name' => 'nullable|string|max:255',
            'project_location' => 'nullable|string|max:255',
            'plot_or_flat_number' => 'nullable|string|max:100',
            'acquisition_type' => 'nullable|in:Registry,Agreement',
            'document' => 'nullable|file|mimes:pdf,jpg,png|max:4096',
            'notes' => 'nullable|string',
        ]);

        $path = null;

        // 🔹 Handle document upload if available
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('investment-inventories', 'public');
        }

        // 🧹 Remove non-column keys before saving
        unset($validated['document']);

        // 🔹 Save or update inventory record
        $inventory = InvestmentInventories::updateOrCreate(
            [
                'user_investment_id' => $validated['user_investment_id'],
            ],
            array_merge($validated, [
                'document_path' => $path,
                'updated_at' => now(),
            ])
        );

        // 🔹 Update guaranty_type in user_investments table
        UserInvestment::where('id', $validated['user_investment_id'])
            ->update(['guaranty_type' => $validated['guaranty_type']]);

        return response()->json([
            'success' => true,
            'message' => 'Guaranty inventory and investment updated successfully!',
            'data' => $inventory,
        ]);
    }




    public function saveInvestmentPayment(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'user_investment_id' => 'required|exists:user_investments,id',
            'payment_mode' => 'required|in:Cash,Cheque,OnlineBanking,Crypto',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'transaction_id' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'cheque_number' => 'nullable|string',
            'crypto_network' => 'nullable|string',
            'wallet_address' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $payment = UserInvestmentPayment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully!',
            'data' => $payment
        ]);
    }



}
