<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */

    public function store(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'currency' => ['required', 'string']
        ]);


        $invoice = auth()->user()->invoices()->create([
            'amount_in_usd' => castDecimalString($request->amount, 2),
            'coin' => strtolower($request->currency),
            'status' => 'pending',
            'plan_id' => 0,
        ]);

        if (!$invoice) {
            throw ValidationException::withMessages([
                'invoice' => 'Error while creating invoice',
            ]);
        }
        return redirect()->route('deposit.add.fund.form', $invoice->id);

    }

    public function status(Invoice $invoice)
    {
        // Security: ensure invoice belongs to logged in user
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }

        return response()->json([
            'status' => $invoice->status
        ]);
    }


}
