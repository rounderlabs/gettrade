<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessUsdWithdrawalUsingAPIlJob;
use App\Methods\OtpMethod;
use App\Models\WithdrawalHistory;
use App\Models\WithdrawalTemp;
use App\Models\WithdrawCoin;
use App\Notifications\OtpNotification;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WithdrawController extends Controller
{

    public function withdrawSetup()
    {
        $user = auth()->user();

        $cryptoWallet = $user->withdrawWallets()
            ->whereHas("withdrawCoin", function ($q) {
                $q->where("name", "bep20_usdt");
            })
            ->first();

        return Inertia::render("Withdraw/WithdrawSetup", [
            "user" => $user,
            "kyc" => $user->kyc,
            "crypto_wallet" => $cryptoWallet,
        ]);
    }


    public function saveWithdrawSetup(Request $request)
    {
        $request->validate([
            "withdraw_mode" => "required|in:INR,CRYPTO",
            "withdraw_type" => "required|in:MANUAL,AUTO",
        ]);

        $user = auth()->user();

        // Only one allowed → overwrite
        $user->update([
            "withdraw_mode" => $request->withdraw_mode,
            "withdraw_type" => $request->withdraw_type,
        ]);

        return redirect()->route("withdraw.redirect")
            ->with("notification", ["Withdrawal settings saved!", "success"]);
    }


    public function redirectWithdraw()
    {
        $user = auth()->user();

        // If user never selected mode
        if (!$user->withdraw_mode) {
            return redirect()->route("withdraw.setup")
                ->with("notification", ["Please select withdrawal mode first", "danger"]);
        }

        /* =======================
           INR MODE
        ======================= */
        if ($user->withdraw_mode === "INR") {

            if (!$user->kyc || $user->kyc->status !== "approved") {
                return redirect()->route("kyc.index")
                    ->with("notification", ["Complete KYC approval first", "danger"]);
            }

            return redirect()->route("withdraw.send.request");
        }

        /* =======================
           CRYPTO MODE
        ======================= */
        if ($user->withdraw_mode === "CRYPTO") {

            $wallet = $user->withdrawWallets()
                ->whereHas("withdrawCoin", function ($q) {
                    $q->where("name", "bep20_usdt");
                })
                ->first();

            if (!$wallet) {
                return redirect()->route("withdraw.wallet.usdt")
                    ->with("notification", ["Update your USDT wallet address first", "danger"]);
            }

            return redirect()->route("withdraw.fund");
        }

        return redirect()->route("withdraw.setup");
    }


    public function withdrawRequestForm()
    {
        $userIncomeWallet = userIncomeWallet(auth()->user());
        return Inertia::render('Withdraw/WithdrawRequest', [
            'available_balance'=>$userIncomeWallet->balance,
        ]);
    }

    public function submitWithdrawRequestForm(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'amount' => ['required', 'numeric', 'gte:10',
                function ($attribute, $value, $fail) {
                    if (is_null(auth()->user()->userIncomeWallet) || userIncomeWallet(\auth()->user())->balance < $value) {
                        $fail('You dont have sufficient balance');
                    }
                    if (castDecimalString($value, 2) < '10.00') {
                        $fail('Minimum amount should be 10 ');
                    }

                    if (WithdrawalHistory::where('user_id', auth()->user()->id)->whereDate('created_at', now()->format('Y-m-d'))->exists()) {
                        $fail('Only one withdrawal is allowed per day.');
                    }

                    if (userStop(\auth()->user())->withdrawal) {
                        $fail('Unable to process request at this time');
                    }
                },

            ],

        ]);
        $userIncomeWallet = userIncomeWallet($user);

        $withdrawal_amount = $userIncomeWallet->balance;
        $kyc = $user->kyc;
        if ($kyc->status == 'approved'){
            return redirect()->route('kyc.index')->with('notification', ['Update Your KYC, One Approve Then Only You Can Request Withdraw', 'danger']);
        }

        if ($withdrawal_amount < $request->amount){
            return redirect()->back()->with('notification', ['You dont have sufficient balance', 'danger']);
        }

        $withdrawAmount = castDecimalString($request->amount, 2);
        $fees = multipleDecimalStrings($withdrawAmount, '0.10', 2);

        $userIncomeWallet = $user->userIncomeWallet()->select('id', 'balance', 'balance_on_hold')->first();
        $userIncomeWallet->decrement('balance', $withdrawAmount);
        $userIncomeWallet->increment('balance_on_hold', $withdrawAmount);

        WithdrawalHistory::create([
            'user_id' => $user->id,
            'bank_name'=>$kyc->bank_name,
            'bank_ifsc'=>$kyc->ifsc_code,
            'bank_account_no'=>$kyc->account_number,
            'upi_id'=>$kyc->upi_id,
            'upi_number'=>$kyc->upi_number,
            'fees' => $fees,
            'amount' => $withdrawAmount,
            'receivable_amount' => subDecimalStrings($withdrawAmount, $fees, 8),
            'status' => 'pending'
        ]);
        return redirect()->route('history.withdrawal')->with('notification', ['Withdrawal submitted successfully', 'success']);
    }


    public function showUsdtAccountForm()
    {
        $coin = WithdrawCoin::where('name', 'bep20_usdt')->first();

        $wallet = $coin?->withdrawWallets()
            ->where('user_id', auth()->id())
            ->first();

        return Inertia::render('Withdraw/WithdrawWalletAddressForm', [
            'withdraw_address' => $wallet?->address ?? "",
            'address_qr' => $wallet
                ? base64_encode(QrCode::size(250)->generate($wallet->address))
                : null,
        ]);
    }

    public function sendOtp(Request $request)
    {
        $this->sendOtpOnEmail($request);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully'
        ]);
    }

    private function sendOtpOnEmail(Request $request)
    {
        $otpModel = $request->user()->otps()->where('is_used', false)->orderByDesc('id')->first();

        if (is_null($otpModel) || now()->unix() > $otpModel->expire_at->unix()) {
            $otpModel = OtpMethod::init()->create()->save($request->user(), 30);
        }
        $request->user()->notify(new OtpNotification($otpModel->code));

    }

    public function updateUsdtWallet(Request $request)
    {
        $request->validate([
            'address' => ['required', 'string']
        ]);

        $this->validateEmailOtp($request);
        DB::transaction(function () use (&$request) {
            $otpModel = Auth::user()->otps()->where('code', $request->otp)->where('is_used', false)->orderByDesc('id')->first();
            $withdrawCoin = WithdrawCoin::where('name', 'bep20_usdt')->first();
            $withdrawWallet = auth()->user()->withdrawWallets()->where('withdraw_coin_id', $withdrawCoin->id)->first();

            if ($withdrawWallet) {
                $withdrawWallet->update([
                    'address' => $request->address
                ]);
            } else {
                auth()->user()->withdrawWallets()->create([
                    'withdraw_coin_id' => $withdrawCoin->id,
                    'address' => $request->address
                ]);
            }

            $otpModel->update([
                'is_used' => true
            ]);
        });


        return back()->with('notification', ['Address updated successfully']);
    }

    private function validateEmailOtp(Request $request)
    {

        $request->validate([
            'otp' => ['required', 'digits:6',
                function ($attribute, $value, $fail) {
                    $otpModel = Auth::user()->otps()->where('code', $value)->where('is_used', false)->orderByDesc('id')->first();
                    if (is_null($otpModel)) {
                        $fail("OTP does not exists.");
                    } elseif (now()->unix() > $otpModel->expire_at->unix()) {
                        $fail("OTP has expired. Please to resend again.");
                    }
                }
            ]
        ]);

    }

    public function withdrawUsdt()
    {
        $baseCurrency    = 'INR';
        $displayCurrency = $user->preferred_currency ?? 'INR';
        $userIncomeWallet = userIncomeWallet(auth()->user());
        return Inertia::render('Withdraw/WithdrawUsdt', [
           // 'income_wallet' => userIncomeWallet(auth()->user()),
            'usd_wallet' => userUsdWallet(auth()->user()),
            'user_income_stats'=>userIncomeStat(auth()->user()),
            'withdrawable_balance'=>$userIncomeWallet->balance,
            'income_wallet' => [
                'balance_base'    => $userIncomeWallet->balance,
                'balance_display' => CurrencyService::convert(
                    (string) $userIncomeWallet->balance,
                    $baseCurrency,
                    $displayCurrency
                ),
            ],
            'currencySymbol'  => $displayCurrency,
        ]);
    }



    public function withdrawUsdtAttempt(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'gte:10',
                function ($attribute, $value, $fail) {
                    if (is_null(auth()->user()->userIncomeWallet) || userIncomeWallet(\auth()->user())->balance < $value) {
                        $fail('You dont have sufficient balance');
                    }
                    if (castDecimalString($value, 2) < '10.00') {
                        $fail('Minimum amount should be 10 ');
                    }

                    if (WithdrawalHistory::where('user_id', auth()->user()->id)->whereDate('created_at', now()->format('Y-m-d'))->exists()) {
                        $fail('Only one withdrawal is allowed per day.');
                    }

//                    $amountToday = WithdrawalHistory::where('user_id', auth()->user()->id)->whereDate('created_at', now()->format('Y-m-d'))->sum('amount');
//                    $userWithdrawalLimit = UserWithdrawalLimit::where('user_id', auth()->user()->id)->first();
//                    if (!is_null($userWithdrawalLimit) && castDecimalString($userWithdrawalLimit->daily_limit, 4) > castDecimalString(0, 4)) {
//                        if (castDecimalString($amountToday ?? 0, 2) >= castDecimalString($userWithdrawalLimit->daily_limit, 2) || (castDecimalString($amountToday ?? 0, 2) + castDecimalString($value, 2)) > castDecimalString($userWithdrawalLimit->daily_limit, 2)) {
//                            $fail('Maximum Withdrawal limit is $' . $userWithdrawalLimit->daily_limit . ' per day!');
//                        }
//                    } else {
//                        if (castDecimalString($amountToday ?? 0, 2) >= castDecimalString(100, 2) || (castDecimalString($amountToday ?? 0, 2) + castDecimalString($value, 2)) > castDecimalString(100, 2)) {
//                            $fail('Maximum Withdrawal limit is $100 per day!');
//                        }
//                    }

                    if (userStop(\auth()->user())->withdrawal) {
                        $fail('Unable to process request at this time');
                    }
                },
//                function ($attribute, $value, $fail) {
//                    $fail("Unable to process at thi time.");
//                }
            ],

        ]);
        $userIncomeWallet = userIncomeWallet(\auth()->user());
        $userIncomeStats = userIncomeStat(\auth()->user());
        $withdrawable_amount = $userIncomeWallet->balance;
        $withdrawWallet = auth()->user()->withdrawWallets()->where('withdraw_coin_id', 1)->first();
        if (!$withdrawWallet){
            return redirect()->route('account.withdraw.wallet')->with('notification', ['Withdraw Address not yet updated', 'danger']);
        }

        if ($withdrawable_amount < $request->amount){
            return redirect()->back()->with('notification', ['You dont have sufficient balance', 'danger']);
        }

        $usdAmount = castDecimalString($request->amount, 2);
        $fees = multipleDecimalStrings($usdAmount, '0.10', 2);
        $withdrawAddress = $withdrawWallet->address;

        $withdrawalTemp = auth()->user()->withdrawalTemps()->create([
            'withdraw_coin_id' => 1,
            'address' => $withdrawAddress,
            'tokens' => castDecimalString('0', 8),
            'token_price' => castDecimalString('0', 4),
            'withdrawal_crypto_price' => castDecimalString('1', 8),
            'fees' => $fees,
            'amount' => $usdAmount,
            'receivable_amount' => subDecimalStrings($usdAmount, $fees, 8),
            'status' => 'pending'
        ]);
        $otpModel = OtpMethod::init()->create()->save($request->user(), 30);
        $request->user()->notify(new OtpNotification($otpModel->code));
        return back()->with('message', ['withdraw_id' => $withdrawalTemp->id]);
    }

    public function withdrawVerifyOtp(Request $request)
    {
        $request->validate([
            'withdraw_id' => ['required', 'numeric', 'exists:withdrawal_temps,id',
                function ($attribute, $value, $fail) {
                    $withdrawHistory = WithdrawalTemp::find($value);
                    if ($withdrawHistory->status != 'pending') {
                        $fail("Invalid request.");
                    } elseif ($withdrawHistory->user_id != Auth::user()->id) {
                        $fail("You are not authorized to request this withdrawal");
                    } elseif (userStop(\auth()->user())->withdrawal) {
                        $fail('Unable to process request at this time');
                    }

                    if (WithdrawalHistory::where('user_id', auth()->user()->id)->whereDate('created_at', now()->format('Y-m-d'))->exists()) {
                        $fail('Only one withdrawal is allowed per day.');
                    }

                }
            ],
            'otp' => ['required', 'digits:6',
                function ($attribute, $value, $fail) {
                    $otpModel = Auth::user()->otps()->where('code', $value)->where('is_used', false)->orderByDesc('id')->first();
                    if (is_null($otpModel)) {
                        $fail("Invalid OTP");
                    }
                    if (now()->unix() > $otpModel->expire_at->unix()) {
                        $fail("OTP has expired. Please to resend again.");
                    }
                }
            ]
        ]);


        $otpModel = Auth::user()->otps()->where('code', $request->otp)->where('is_used', false)->orderByDesc('id')->first();
        $otpModel->update([
            'is_used' => true
        ]);

        $withdrawalTemp = WithdrawalTemp::find($request->withdraw_id);


        if ($withdrawalTemp->amount > userIncomeWallet(\auth()->user())->balance) {
            $withdrawalTemp->update([
                'status' => 'failed'
            ]);

            return redirect()->back()->with('notification', ['You dont have sufficient balance', 'danger']);
        }
        $withdrawalHistory = null;

        DB::transaction(function () use (&$withdrawalTemp, &$request, &$withdrawalHistory) {
            $userCoinWallet = auth()->user()->userIncomeWallet()->select('id', 'balance', 'balance_on_hold')->first();
            $userCoinWallet->decrement('balance', $withdrawalTemp->amount);
            $userCoinWallet->increment('balance_on_hold', $withdrawalTemp->amount);

            $withdrawalHistory = $request->user()->withdrawalHistories()->create([
                'txn_id' => null,
                'withdraw_coin_id' => $withdrawalTemp->withdraw_coin_id,
                'address' => $withdrawalTemp->address,
                'tokens' => $withdrawalTemp->tokens,
                'token_price' => $withdrawalTemp->token_price,
                'withdrawal_crypto_price' => $withdrawalTemp->withdrawal_crypto_price,
                'fees' => $withdrawalTemp->fees,
                'amount' => $withdrawalTemp->amount,
                'receivable_amount' => $withdrawalTemp->receivable_amount,
                'status' => 'pending'
            ]);
            $withdrawalTemp->update([
                'status' => 'success'
            ]);
        });

        ProcessUsdWithdrawalUsingAPIlJob::dispatch($withdrawalHistory)->delay(now());

        return redirect()->route('history.withdrawal')->with('notification', ['Withdrawal submitted successfully', 'success']);


    }

}
