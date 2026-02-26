<?php

namespace App\Http\Controllers;

use App\Jobs\CreateSubscriptionJob;
use App\Models\AdminFundWallet;
use App\Models\CryptApiWallet;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\UserIncomeWallet;
use App\Models\UserUsdWallet;
use App\Models\UserUsdWalletTransaction;
use App\Services\AdminNotificationService;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PurchaseController extends Controller
{
    public function addFundInWallet()
    {
        return Inertia::render('Deposit/AddFundInWallet', [
            'currencies' => getDepositCoins()
        ]);
    }

    public function showAddFundForm(Request $request, Invoice $invoice)
    {
        $validator = Validator::make(['status' => $invoice->status], [
            'status' => ['required', 'string', Rule::in(['pending'])]
        ]);
        if ($validator->fails()) {
            abort(404);
        }

        $cryptApiWallet = auth()->user()->cryptApiWallets()->where('invoice_id', $invoice->id)->first();
        if (is_null($cryptApiWallet)) {
            $cryptApiWallet = $this->generateNewAddress($invoice->coin, $invoice->id);
            if (is_null($cryptApiWallet)) {
                return back()->with('notification', [
                    'Unable to generate deposit address. Please try again later.',
                    'danger'
                ]);
            }
        }

        if (in_array($invoice->coin, ['trc20_usdt'])) {
            $depositAmountInCrypto = $invoice->amount_in_usd;
        } else {
            $extraPercentDecimal = '0.0198';
            $usdAmount = addDecimalStrings($invoice->amount_in_usd, multipleDecimalStrings($invoice->amount_in_usd, $extraPercentDecimal, 2), 2);

            $depositAmountInCrypto = divDecimalStrings($usdAmount, getDepositCoinPrice($invoice->coin), 6);
        }

        return Inertia::render('Deposit/PaymentAddress', [
            'invoice_id' => $invoice->id,
            'address' => $cryptApiWallet->address_in,
            'qr' => base64_encode(QrCode::size(250)->generate($cryptApiWallet->address_in)),
            'currency' => $invoice->coin,
            'amount' => $depositAmountInCrypto,
            'explorer' => config('explorers.' . $invoice->coin . ".address") . $cryptApiWallet->address_in
        ]);
    }


    public function planActivate(Request $request)
    {
       // return $request;
        $request->validate([
            'plan_id' => ['required', 'numeric', 'exists:plans,id'],
            'amount'  => ['required', 'numeric'], // INR amount (base)
        ]);

        $user = auth()->user();
        $plan = Plan::where('id', $request->plan_id)->first();

        /**
         * ------------------------------------------------
         * BASE VALUES (INR)
         * ------------------------------------------------
         */
        $amountInInr = castDecimalString($plan->amount, 2);

        if ($amountInInr != $plan->amount) {
            return back()->with('notification', [
                'Entered amount is invalid',
                'danger'
            ]);
        }



        DB::beginTransaction();

        $wallet = UserUsdWallet::where('user_id', $user->id)
            ->lockForUpdate()
            ->first();

        if (! $wallet || bccomp($wallet->balance, $amountInInr, 2) < 0) {
            DB::rollBack();
            return back()->with('notification', [
                'Insufficient wallet balance',
                'danger'
            ]);
        }

        /**
         * ------------------------------------------------
         * WALLET TRANSACTION (UNCHANGED STRUCTURE ✅)
         * ------------------------------------------------
         */
        $walletTransaction = UserUsdWalletTransaction::create([
            'user_id'          => $user->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd'    => $amountInInr,
            'last_amount'      => $wallet->balance,
            'summary'          => 'Investment',
            'status'           => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time'         => now(),
        ]);

        $wallet->decrement('balance', $amountInInr);

        DB::commit();

        /**
         * ------------------------------------------------
         * SUBSCRIPTION JOB (PLAN IS INR-BASED)
         * ------------------------------------------------
         */
        CreateSubscriptionJob::dispatch(
            $plan,
            $user,
            $walletTransaction
        )->delay(now()->addSecond());
        AdminNotificationService::notify(
            'activation',
            "🚀 <b>User Activated</b>\nUsername: {$user->username}\nPlan: {$plan->name}"
        );
        return redirect()
            ->route('dashboard')
            ->with('notification', [
                'Package subscribed successfully',
                'success'
            ]);
    }


    function isMultipleOfFifty($number){
        $result = $number % 50;
        if ($result == 0 ){
            return true;
        }else{
            return false;
        }

    }

    public function showPricing()
    {
        $user = auth()->user();

        $baseCurrency = 'INR';
        $displayCurrency = $user->preferred_currency ?? 'INR';

        $plans = Plan::where('is_active', 1)
            ->where('allow_to_user', 1)
            ->orderBy('id', 'ASC')
            ->get()
            ->map(function ($plan) use ($baseCurrency, $displayCurrency) {

                $plan->display_amount = CurrencyService::convert(
                    (string) $plan->amount,
                    $baseCurrency,
                    $displayCurrency
                );

                return $plan;
            });

        return Inertia::render('Purchase/Pricing', [
            'plans' => $plans,
            'display_currency' => $displayCurrency,
        ]);
    }


    public function showTopUpForm(Request $request, $planId)
    {
        $plan = Plan::find($planId);
        $user = auth()->user();
        $baseCurrency = 'INR'; // wallet + plans stored in USD
        $displayCurrency = $user->preferred_currency ?? 'INR';

        return Inertia::render('Purchase/Buy', [
            'plan' => [
                'id' => $plan->id,
                'amount_base' => $plan->amount, // ✅ NEVER converted
                'amount_display' => CurrencyService::convert(
                    (string) $plan->amount,
                    $baseCurrency,
                    $displayCurrency
                ),
                'monthly_roi_amount' => $plan->monthly_roi_amount,
                'tenure' => $plan->tenure,
            ],

            'available_coin_balance' => [
                'balance_base' => userUsdWallet($user)->balance,
                'balance_display' => CurrencyService::convert(
                    (string) userUsdWallet($user)->balance,
                    $baseCurrency,
                    $displayCurrency
                ),
            ],

            'display_currency' => $displayCurrency,
        ]);
    }


    public function showPaymentForm(Request $request, Invoice $invoice)
    {
        $validator = Validator::make(['status' => $invoice->status], [
            'status' => ['required', 'string', Rule::in(['pending'])]
        ]);


        if ($validator->fails()) {
            abort(404);
        }

        $cryptApiWallet = auth()->user()->cryptApiWallets()->where('invoice_id', $invoice->id)->first();
        if (is_null($cryptApiWallet)) {
            $cryptApiWallet = $this->generateNewAddress($invoice->coin, $invoice->id);
            if (is_null($cryptApiWallet)) {
                return redirect()->back();
            }
        }


        if ($invoice->coin == 'trx') {
            $extraPercentDecimal = '0.00';
//            $usdAmount = addDecimalStrings($plan->start_price_usd, multipleDecimalStrings($plan->start_price_usd, $extraPercentDecimal, 2), 2);
//            $depositAmountInCrypto = divDecimalStrings($usdAmount, tronPrice(), 6);

            $usdAmount = addDecimalStrings($invoice->amount_in_usd, multipleDecimalStrings($invoice->amount_in_usd, $extraPercentDecimal, 2), 2);
            $depositAmountInCrypto = divDecimalStrings($usdAmount, tronPrice(), 6);

        } else {
            $depositAmountInCrypto = $invoice->amount_in_usd;
        }
//        $plan = Plan::where('plan_id',$plan);
//        return $plan;
        return Inertia::render('Purchase/PaymentForm', [
            'address' => $cryptApiWallet->address_in,
            'qr' => base64_encode(QrCode::size(250)->generate($cryptApiWallet->address_in)),
            'currency' => $invoice->coin == "bep20_usdt" ? 'USDT' : $invoice->coin,
            'amount' => $depositAmountInCrypto,
            'plan' => $invoice->plan,
            'tenure' => $invoice->stakingIncomePlan->staking_tenure
        ]);


    }

//    private function generateNewAddress($coinName, $invoiceId): ?CryptApiWallet
//    {
//        $pwtFundWallet = AdminFundWallet::where('coin', $coinName)->first();
//        //Log::info($pwtFundWallet);
//        if (is_null($pwtFundWallet)) {
//            return null;
//        }
//        if ($pwtFundWallet->address) {
//            $fundWallet = $pwtFundWallet->address;
//        } else {
//            return null;
//        }
//        $callbackUrl = route('gateway.callback') . "/";
//        $parameters = ['coin' => strtolower($coinName), 'invoice_id' => $invoiceId];
//        $reqParameters = http_build_query($parameters);
//        $callbackUrlComplete = "{$callbackUrl}?{$reqParameters}";
//        $pgResponse = Http::post('https://gateway.eaglebattle.io/api/address/generate/' . $coinName, [
//            'address_out' => $fundWallet,
//            'callback_url' => $callbackUrlComplete,
//        ]);
//        Log::info($pgResponse);
//        if ($pgResponse["status"] == "success") {
//            return auth()->user()->cryptApiWallets()->create([
//                'invoice_id' => $invoiceId,
//                'crypto' => strtolower($coinName),
//                'callback_url' => $callbackUrlComplete,
//                'address_out' => $fundWallet,
//                'address_in' => $pgResponse["address_in"]
//            ]);
//        }
//        return null;
//    }

    private function generateNewAddress($coinName, $invoiceId): ?CryptApiWallet
    {
        $pwtFundWallet = AdminFundWallet::where('coin', $coinName)->first();

        if (!$pwtFundWallet || !$pwtFundWallet->address) {
            Log::error("Fund wallet missing for coin: $coinName");
            return null;
        }

        $fundWallet = $pwtFundWallet->address;

        $callbackUrl = route('gateway.callback');
        $callbackUrlComplete = $callbackUrl . "?" . http_build_query([
                'coin' => strtolower($coinName),
                'invoice_id' => $invoiceId
            ]);

        try {

            $pgResponse = Http::timeout(10)
                ->post("https://gateway.getwealth.live/api/address/generate/$coinName", [
                    'address_out' => $fundWallet,
                    'callback_url' => $callbackUrlComplete,
                ]);

            // ✅ If API fails
            if (!$pgResponse->successful()) {
                Log::error("Gateway API failed", [
                    "coin" => $coinName,
                    "response" => $pgResponse->body()
                ]);
                return null;
            }

            $data = $pgResponse->json();

            // ✅ If invalid JSON
            if (!$data || !isset($data['status'])) {
                Log::error("Invalid gateway response", [
                    "coin" => $coinName,
                    "response" => $pgResponse->body()
                ]);
                return null;
            }

            // ✅ Success
            if ($data['status'] === "success") {

                return auth()->user()->cryptApiWallets()->create([
                    'invoice_id'   => $invoiceId,
                    'crypto'       => strtolower($coinName),
                    'callback_url' => $callbackUrlComplete,
                    'address_out'  => $fundWallet,
                    'address_in'   => $data["address_in"] ?? null,
                ]);
            }

            Log::error("Gateway returned failure", $data);

        } catch (\Exception $e) {

            Log::error("Gateway exception", [
                "coin" => $coinName,
                "error" => $e->getMessage()
            ]);

            return null;
        }

        return null;
    }

}
