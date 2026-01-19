<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EarningController;
use App\Http\Controllers\GatewayApiController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\MagicController;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WithdrawController;
use App\Models\UserIncomeStat;
use App\Models\UserIncomeWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/', [HomeController::class, 'index'])->name('home');
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
Route::get('get-countries', [CountryController::class, 'getCountries'])->name('countries.get');




//Route::post('validate/user', [UserController::class, 'isUserExistByRefCode'])->name('validate.user.refcode');
Route::post('validate/username', [UserController::class, 'isUserExistByRefCode'])->name('validate.user');
Route::post('validate/check-username', [UserController::class, 'isUsernameExist'])->name('validate.username');

Route::get('gateway/callback', [GatewayApiController::class, 'processRequest'])->name('gateway.callback');
Route::middleware(['auth'])->group(function () {

    Route::get('menu', [DashboardController::class, 'showMenu'])->name('menu');
    Route::get('dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::post('update-welcome-preference', [DashboardController::class, 'updateWelcomePreference'])->name('welcome.preference');
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('profile', [AccountController::class, 'index'])->name('profile');
        Route::get('details', [AccountController::class, 'accountDetails'])->name('details');
        Route::get('profile-picture', [AccountController::class, 'profilePictureForm'])->name('profile.picture');
        Route::get('setting', [AccountController::class, 'settingAll'])->name('setting');
        Route::get('change-password', [AccountController::class, 'showChangePassword'])->name('change.password');
        Route::get('withdraw-wallet', [AccountController::class, 'withdrawWallet'])->name('withdraw.wallet');
        Route::post('update-password', [AccountController::class, 'updatePassword'])->name('update.password');
        Route::post('update-profile', [AccountController::class, 'updateProfile'])->name('update.profile');
        Route::post('update-profile-picture', [AccountController::class, 'updateProfilePicture'])->name('update.profile.picture');
        Route::post('update-profile-active', [AccountController::class, 'updateActiveProfile'])->name('update.profile.active');
    });

    Route::prefix('history')->name('history.')->group(function () {
        Route::get('deposit', [HistoryController::class, 'showDepositHistory'])->name('deposit');
        Route::get('withdrawal', [HistoryController::class, 'showWithdrawalHistory'])->name('withdrawal');
        Route::get('wallet-txn', [HistoryController::class, 'showWalletTxnHistory'])->name('wallet.usd');
        Route::get('get-wallet-txn', [HistoryController::class, 'getWalletTxnHistory'])->name('wallet.usd.get');
    });


    Route::prefix('team')->name('team.')->group(function () {
        Route::get('genealogy', [TeamController::class, 'showGenealogy'])->name('genealogy');
        Route::get('genealogy/{user?}/{position?}', [TeamController::class, 'showGenealogy'])->name('genealogy.sub');

        Route::get('direct', [TeamController::class, 'showDirectPartner'])->name('direct');
        Route::get('get-direct-list', [TeamController::class, 'getDirectPartners'])->name('direct.list');

        Route::get('active-direct', [TeamController::class, 'showActiveDirectPartner'])->name('active.direct');
        Route::get('get-active-direct-list', [TeamController::class, 'getActiveDirectPartners'])->name('active.direct.list');

        Route::get('team-by-level', [TeamController::class, 'showTeamByLevel'])->name('by.level');
        Route::get('by-level-list/{level?}', [TeamController::class, 'getTeamByLevel'])->name('by.level.list');

        Route::get('active-team-by-level', [TeamController::class, 'showActiveTeamByLevel'])->name('active.by.level');
        Route::get('active-by-level-list/{level?}', [TeamController::class, 'getActiveTeamByLevel'])->name('active.by.level.list');


        Route::post('search-downline', [TeamController::class, 'seachDownline'])->name('search.downline');
    });

    Route::prefix('earnings')->name('earnings.')->group(function () {

        Route::get('level-bonus', [EarningController::class, 'showFrontLineBonus'])->name('front.line.bonus');
        Route::get('level-bonus-get', [EarningController::class, 'getFrontLineBonus'])->name('front.line.bonus.get');

        Route::get('monthly-dividend-bonus', [EarningController::class, 'showMonthlyTradingBonus'])->name('monthly.trading.bonus');
        Route::get('monthly-roi-bonus-get', [EarningController::class, 'getMonthlyTradingBonus'])->name('monthly.trading.bonus.get');

        Route::get('level-on-dividend-bonus', [EarningController::class, 'showProfitSharingBonus'])->name('profit.sharing.bonus');
        Route::get('level-on-roi-bonus-get', [EarningController::class, 'getProfitSharingBonus'])->name('profit.sharing.bonus.get');

        Route::get('maturity-bonus', [EarningController::class, 'showMaturityBonus'])->name('maturity.bonus');
        Route::get('maturity-bonus-get', [EarningController::class, 'getMaturityBonus'])->name('maturity.bonus.get');

        Route::get('pool-bonus', [EarningController::class, 'showPoolBonus'])->name('pool.bonus');
        Route::get('get-pool-bonus', [EarningController::class, 'getPoolBonus'])->name('pool.bonus.get');

        Route::get('reward-bonus', [EarningController::class, 'showRewardBonus'])->name('reward.bonus');
        Route::get('get-reward-bonus', [EarningController::class, 'getRewardBonus'])->name('reward.bonus.get');


    });

    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::get('pricing', [PurchaseController::class, 'showPricing'])->name('pricing');
        //   Route::get('pricing', [DashboardController::class, 'maintenance'])->name('pricing');
        Route::get('subscription/{planId}', [PurchaseController::class, 'showTopUpForm'])
            ->where(['planId' => '[0-9]+'])->name('topup.form');

        Route::post('plan', [PurchaseController::class, 'planActivate'])->name('plan.activate');
    });




    Route::prefix('deposit')->name('deposit.')->group(function () {
        Route::get('add-fund', [PurchaseController::class, 'addFundInWallet'])->name('add.fund');
        Route::post('fund-request', [WalletController::class, 'submitAddFundRequest'])->name('fund.request.submit');
        Route::post('invoice', [InvoiceController::class, 'store'])->name('invoice.create');
        Route::get('add-fund-form/{invoice}', [PurchaseController::class, 'showAddFundForm'])->name('add.fund.form');
    });

    Route::prefix('withdraw')->name('withdraw.')->group(function () {
        Route::get('send-request', [WithdrawController::class, 'withdrawRequestForm'])->name('send.request');
        Route::post('submit-request', [WithdrawController::class, 'submitWithdrawRequestForm'])->name('submit.request');
    });



    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', [KycController::class, 'index'])->name('index');
        Route::post('step1', [KycController::class, 'step1'])->name('step1');
        Route::post('step2', [KycController::class, 'step2'])->name('step2');
        Route::post('step3', [KycController::class, 'step3'])->name('step3');
        Route::get('status', [KycController::class, 'status'])->name('status');
        Route::get('resubmit', [KycController::class, 'resubmit'])
            ->name('resubmit');
    });
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('fund-transfer', [WalletController::class, 'showFundTransferForm'])->name('fund.transfer');
        Route::post('submit-fund-transfer', [WalletController::class, 'submitFundTransferFrom'])->name('fund.transfer.submit');

        Route::get('activate-user', [WalletController::class, 'showActivateMemberForm'])->name('activate.user');
        Route::post('submit-activation-form', [WalletController::class, 'submitActivationForm'])->name('activation.user.submit');

        Route::get('swap-inr-usd', [WalletController::class, 'showInrToUsdSwapForm'])->name('swap.inr.usd');
        Route::post('submit-swap-inr-usd', [WalletController::class, 'submitInrToUsdSwapForm'])->name('submit.swap.inr.usd');

        Route::get('swap-usd-inr', [WalletController::class, 'showUsdToInrSwapForm'])->name('swap.usd.inr');
        Route::post('submit-swap-usd-inr', [WalletController::class, 'submitUsdToInrSwapForm'])->name('submit.swap.usd.inr');

        Route::get('add-fund-request', [WalletController::class, 'showInrRequest'])->name('add.fund.request');
        Route::get('get-add-fund-request', [WalletController::class, 'getInrRequest'])->name('get.add.fund.request');

        Route::get('ledger', [WalletController::class, 'showLedger'])->name('ledger');
        Route::get('get-ledger', [WalletController::class, 'getLedger'])->name('ledger.get');

    });

});


Route::middleware('auth')->get('/sidebar-data', function (Request $request) {
    $user = $request->user();
    $wallet = UserIncomeStat::where('user_id', $user->id)->first();
    return response()->json([
        'balance' => $wallet->total ?? 0,
    ]);
});



