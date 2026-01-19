<?php

namespace App\Http\Controllers;

use App\Jobs\CreateSubscriptionJob;
use App\Models\AdminFundWallet;
use App\Models\CryptApiWallet;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\UserIncomeWallet;
use App\Models\UserRoiCompounding;
use App\Models\UserUsdWallet;
use App\Models\UserUsdWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function userTokenWallet;

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
                return redirect()->back();
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
            'address' => $cryptApiWallet->address_in,
            'qr' => base64_encode(QrCode::size(250)->generate($cryptApiWallet->address_in)),
            'currency' => $invoice->coin,
            'amount' => $depositAmountInCrypto,
            'explorer' => config('explorers.' . $invoice->coin . ".address") . $cryptApiWallet->address_in
        ]);
    }


    public function planActivate(Request $request)
    {
        $request->validate([
            'plan_id' => ['required', 'numeric', 'exists:plans,id'],
            'amount' => ['required', 'numeric'],
        ]);
        $user = auth()->user();
        $plan = Plan::where('id', $request->plan_id)->first();
        $amount = castDecimalString($request->amount, 2);
        $userID = auth()->user()->id;

        DB::beginTransaction();
        $userUsdWallet = UserUsdWallet::where('user_id', $userID)->lockForUpdate()->first();
        if (is_null($userUsdWallet)) {
            DB::rollBack();
            return redirect()->back()->with('notification', ['Insufficient Balance', 'danger']);
        }



        if ($amount != $plan->amount) {
            DB::rollBack();
            return redirect()->back()->with('notification', ['Entered Amount is not in valid.', 'danger']);
        }
        if ($userUsdWallet->balance < $amount) {
            DB::rollBack();
            return redirect()->back()->with('notification', ['Insufficient Balance', 'danger']);
        }

        $walletTransaction = UserUsdWalletTransaction::create([
            'user_id' => $request->user()->id,
            'transaction_type' => UserUsdWalletTransaction::TXN_TYPE['DEBIT'],
            'amount_in_usd' => $amount,
            'last_amount' => $userUsdWallet->balance,
            'summary' => 'Investment',
            'status' => UserUsdWalletTransaction::TXN_STATUS['SUCCESS'],
            'txn_time' => now()
        ]);

        $userUsdWallet->decrement('balance', $amount);

        if (is_null($walletTransaction)) {
            DB::rollBack();
            return redirect()->back()->with('notification', ['Please try again later', 'danger']);
        }
        DB::commit();

        CreateSubscriptionJob::dispatch($plan, $request->user(), $walletTransaction)->delay(now()->addSecond());

        return redirect()->route('dashboard')->with('notification', ['Package subscribed successfully', 'success']);

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
        return Inertia::render('Purchase/Pricing', [
            'plans' => Plan::where('is_active', 1)->where('allow_to_user', 1)->orderBy('id', 'ASC')->get(),
        ]);
    }


    public function showTopUpForm(Request $request, Plan $planId)
    {
        return Inertia::render('Purchase/Buy', [
            'plan' => $planId,
            'available_coin_balance' => userUsdWallet(auth()->user()),
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

    private function generateNewAddress($coinName, $invoiceId): ?CryptApiWallet
    {
        $pwtFundWallet = AdminFundWallet::where('coin', $coinName)->first();
        //Log::info($pwtFundWallet);
        if (is_null($pwtFundWallet)) {
            return null;
        }
        if ($pwtFundWallet->address) {
            $fundWallet = $pwtFundWallet->address;
        } else {
            return null;
        }
        $callbackUrl = route('gateway.callback') . "/";
        $parameters = ['coin' => strtolower($coinName), 'invoice_id' => $invoiceId];
        $reqParameters = http_build_query($parameters);
        $callbackUrlComplete = "{$callbackUrl}?{$reqParameters}";
        $pgresponse = Http::post('https://gateway.eaglebattle.io/api/address/generate/' . $coinName, [
            'address_out' => $fundWallet,
            'callback_url' => $callbackUrlComplete,
        ]);
        if ($pgresponse["status"] == "success") {
            return auth()->user()->cryptApiWallets()->create([
                'invoice_id' => $invoiceId,
                'crypto' => strtolower($coinName),
                'callback_url' => $callbackUrlComplete,
                'address_out' => $fundWallet,
                'address_in' => $pgresponse["address_in"]
            ]);
        }
        return null;
    }
}
