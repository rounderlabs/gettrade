<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CurrencyController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Currencies/Index', [
            'currencies' => Currency::orderBy('is_base', 'desc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:currencies,code',
            'name' => 'required|string',
            'symbol' => 'required|string|max:20',
            'currency_type' => 'required|in:fiat,crypto',
        ]);

        Currency::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'symbol' => $data['symbol'],
            'currency_type' => $data['currency_type'],
            'is_active' => true,
            'is_base' => false,
        ]);


        return back()->with('notification', ['Currency added', 'success']);
    }

    public function toggle(Currency $currency)
    {
        if ($currency->is_base) {
            return back()->with('error', 'Base currency cannot be disabled');
        }

        $currency->update([
            'is_active' => ! $currency->is_active,
        ]);

        return back();
    }
}
