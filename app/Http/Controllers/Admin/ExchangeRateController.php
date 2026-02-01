<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExchangeRateController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/ExchangeRates/Index', [
            'rates' => ExchangeRate::latest()->get(),
            'currencies' => Currency::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3|different:from_currency',
            'rate' => 'required|numeric|min:0.000001',
        ]);

        ExchangeRate::updateOrCreate(
            [
                'from_currency' => $data['from_currency'],
                'to_currency' => $data['to_currency'],
            ],
            [
                'rate' => $data['rate'],
                'effective_at' => now(),
            ]
        );

        return back()->with('success', 'Exchange rate updated');
    }
}
