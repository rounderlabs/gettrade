<?php

use App\Http\Controllers\Admin\AdminFundRequestController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWithdrawalReportController;
use App\Http\Controllers\Admin\Api\AdminRoleController;
use App\Http\Controllers\Admin\Api\PermissionController;
use App\Http\Controllers\Admin\Api\RoleController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\ExchangeRateController;
use App\Http\Controllers\Admin\InvestmentController;
use App\Http\Controllers\Admin\ModuleSettingController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\RankRuleController;
use App\Http\Controllers\Admin\ScheduledJobController;
use App\Http\Controllers\Admin\SubscriptionHistoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\UserUsdWalletAdminUpdateController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Inertia::setRootView('admin');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/check', function () {
        return response()->json(['root_view' => config('inertia.root_view', 'app')]);
    });

    // -----------------------
    // Auth
    // -----------------------
    Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login.create');
    Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');

        // Dashboard & menu
        Route::get('dashboard', [AdminDashboardController::class, 'showDashboard'])->name('dashboard');
        Route::get('menu', [AdminDashboardController::class, 'menu'])->name('menu');

        // Roles & Permissions UI (Inertia)
        Route::get('/roles', fn() => Inertia::render('Admin/Roles/Index'))->name('roles.index');
        Route::get('/permissions', fn() => Inertia::render('Admin/Permissions/Index'))->name('permissions.index');
        Route::get('/assign-role', fn() => Inertia::render('Admin/Roles/AssignRole'))->name('roles.assign');

        // Role & Permission API
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);
        Route::post('admins/{admin}/roles', [AdminRoleController::class, 'assignRoles'])->name('admins.assign.roles');
        Route::get('admins/{admin}/roles', [AdminRoleController::class, 'getRoles'])->name('admins.get.roles');

        // USERS
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'showAllUsersPage'])->name('index');
            Route::get('get-users', [AdminUserController::class, 'getUsers'])->name('get_users');
            Route::post('filter-users', [AdminUserController::class, 'filterUsers'])->name('filter_users');
        });

        // WITHDRAWAL
        Route::prefix('withdrawal')->name('withdrawal.')->group(function () {

            Route::get('reports', [AdminWithdrawalReportController::class, 'index'])->name('reports');
            Route::get('export', [AdminWithdrawalReportController::class, 'export'])->name('reports.export');
            Route::post('update-status/{withdrawal}', [AdminWithdrawalReportController::class, 'updateStatus'])->name('update.status');

            Route::get('/', [AdminUserController::class, 'showWithdrawalHistory'])->name('index');
            Route::post('withdrawal-history', [AdminUserController::class, 'withdrawalHistory'])->name('withdrawal_history');
            Route::post('/process', [AdminUserController::class, 'processWithdrawal'])->name('process');
        });

        // DEPOSIT
        Route::prefix('deposit')->name('deposit.')->group(function () {
            Route::get('/', [AdminUserController::class, 'showDepositHistory'])->name('index');
            Route::match(['get', 'post'], 'get-list', [AdminUserController::class, 'getUserDeposits'])->name('get.list');
            Route::post('process-deposit-history', [AdminUserController::class, 'depositHistory'])->name('process_deposit_history');
            Route::get('inr-deposit-request', [AdminUserController::class, 'inrDepositRequest'])->name('inr.request');
            Route::get('get-inr-deposit-request', [AdminUserController::class, 'getInrDepositRequest'])->name('get.inr.request');
            Route::get('get-inr-deposit-request-by-id/{id}', [AdminUserController::class, 'getInrDepositRequestById'])->name('get.inr.request.id');
            Route::post('update-inr-deposit-request', [AdminUserController::class, 'updateDepositRequest'])->name('update.inr.deposit.request');
        });

        // SINGLE USER ACTIONS
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/{user}', [AdminUserController::class, 'showUser'])->name('create');
            Route::post('update-profile', [AdminUserController::class, 'updateProfile'])->name('store');
            Route::post('update-wallet-balance', [UserUsdWalletAdminUpdateController::class, 'updateUserWallet'])->name('update.wallet.balance');
            Route::post('update-income-wallet-balance', [UserUsdWalletAdminUpdateController::class, 'updateUserIncomeWallet'])->name('update.income.wallet.balance');
            Route::post('update-activation-wallet-balance', [UserUsdWalletAdminUpdateController::class, 'updateUserActivationWallet'])->name('update.activation.wallet.balance');
            Route::post('create-user-investment', [UserUsdWalletAdminUpdateController::class, 'createUserInvestment'])->name('create.investment');
            Route::post('fund-deposit', [AdminUserController::class, 'manualDeposit'])->name('fund.deposit');
            Route::get('subscriptions/{user}', [AdminUserController::class, 'showUserSubscriptions'])->name('subscriptions');
            Route::post('verify-email/{user}', [AdminUserController::class, 'verifyUserEmail'])->name('email.verify');
            Route::get('user-panel/{user}', [AdminUserController::class, 'loginByUserId'])->name('access.panel');
            Route::get('get-stop-roi/{user}', [AdminUserController::class, 'getUserStopRoi'])->name('get.stop.roi');
            Route::post('update-stop-roi', [AdminUserController::class, 'UpdateUserStopRoi'])->name('update.stop.roi');
            Route::post('update-withdrawal-limit', [AdminUserController::class, 'UpdateUserWithdrawalLimit'])->name('update.withdrawal.limit');
            Route::post('team-start-stop-withdrawal', [AdminUserController::class, 'teamStartStopWithdrawal'])->name('team.start.stop.withdrawal');
            Route::get('export-team/{user}', [AdminDashboardController::class, 'exportTeam'])->name('export.team');
            Route::get('export-team-package/{user}', [AdminDashboardController::class, 'getTeamSubscriptions'])->name('export.team.package');
            Route::get('show-team-overview/{user}', [AdminDashboardController::class, 'getTeamOverview'])->name('show.team.overview');
            Route::post('update-stops', [AdminUserController::class, 'updateUserStops'])->name('update.stops');
            Route::post('update-manual-level', [AdminUserController::class, 'updateManualLevel'])->name('update.manual.level');
        });

        // SUBSCRIPTIONS
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::get('/', [SubscriptionHistoryController::class, 'Index'])->name('show');
            Route::get('/history', [SubscriptionHistoryController::class, 'getSubscriptionHistory'])->name('get');
        });

        Route::prefix('investment')->name('investment.')->group(function () {
            Route::get('/', [InvestmentController::class, 'index'])->name('index');
            Route::get('get/{user_id?}/{from_date?}/{to_date?}/{guaranty_type?}/{investment_type?}', [InvestmentController::class, 'getInvestments'])->name('get');
            Route::get('details/{id}', [InvestmentController::class, 'show'])->name('details');
            Route::post('settings/save', [InvestmentController::class, 'saveSetting'])->name('settings.save');
            Route::post('inventory/save', [InvestmentController::class, 'saveInvestmentInventory'])->name('inventory.save');
            Route::post('payment/save', [InvestmentController::class, 'saveInvestmentPayment'])->name('payment.save');
        });

        // COMPOUNDING
        Route::prefix('kyc')->name('kyc.')->group(function () {
            Route::get('/', [AdminKycController::class, 'index'])->name('index');
            Route::get('/{kyc}', [AdminKycController::class, 'show'])->name('show');
            Route::post('/{kyc}/approve', [AdminKycController::class, 'approve'])->name('approve');
            Route::post('/{kyc}/reject', [AdminKycController::class, 'reject'])->name('reject');
//            Route::get('/file', function (Request $request) {
//                abort_unless(auth()->user()->is_admin, 403);
//                return Storage::disk('public')->download($request->path);
//            })->name('file');
//            Route::get('/file/{submission}/{field}', function (KycSubmission $submission, $field) {
//
//                $allowedFields = [
//                    'aadhaar_front',
//                    'aadhaar_back',
//                    'pan_file',
//                    'cancel_cheque',
//                ];
//
//                abort_unless(in_array($field, $allowedFields), 403);
//
//                $path = $submission->$field;
//
//                abort_unless($path && Storage::disk('private')->exists($path), 404);
//
//                return Storage::disk('private')->download($path);
//
//            })->name('file');
//

            Route::get('/file/{submission}/{field}',
                [AdminKycController::class, 'download']
            )->name('file');

        });

        // REFUND
//        Route::prefix('refund')->name('refund.')->group(function () {
//            Route::get('/', [AdminUserController::class, 'showUserRefundRequest'])->name('request');
//            Route::get('request-get', [AdminUserController::class, 'getUserRefundRequest'])->name('request.get');
//        });

        // PASSWORD & MODULE SETTINGS
        Route::get('change-password', [AdminUserController::class, 'changePassword'])->name('change.password');
        Route::post('update-password', [AdminUserController::class, 'updatePassword'])->name('update.password');
        Route::get('module-setting', [ModuleSettingController::class, 'Index'])->name('module.setting');
        Route::post('module-setting-update', [ModuleSettingController::class, 'updateModuleSetting'])->name('module.setting.update');
        Route::get('user-overview', [ModuleSettingController::class, 'getUserOverview'])->name('user.overview');

        // KYC REQUESTS
        Route::get('users-kyc-request', [AdminUserController::class, 'userKycRequest'])->name('user.kyc.request');
        Route::get('get-users-kyc-request', [AdminUserController::class, 'getUserKycRequest'])->name('get.user.kyc.request');
        Route::get('view-kyc-request/{id}', [AdminUserController::class, 'viewKycById'])->name('view.kyc.request');
        Route::post('update-kyc-request', [AdminUserController::class, 'updateKycStatus'])->name('update.kyc.request');
    });

    // API
    Route::prefix('api/admin')->name('api.admin.')->middleware(['auth:admin'])->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);
        Route::post('admins/{admin}/assign-role', [AdminRoleController::class, 'assignRole'])->name('admins.assign-role');
        Route::get('admins/{admin}/roles', [AdminRoleController::class, 'getRoles'])->name('admins.get-roles');
    });


    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('user-trading-bonus-report', [AdminReportController::class, 'userTradingBonus'])->name('user.trading.bonus');
        Route::get('get-user-trading-bonus-report', [AdminReportController::class, 'getUserTradingBonus'])->name('get.user.trading.bonus');
        Route::get('user-trading-bonus/export', [AdminReportController::class, 'exportUserTradingBonus'])->name('user.trading.bonus.export');

        Route::get('user-level-roi-bonus', [AdminReportController::class, 'userTradeProfitBonus'])->name('user.level.roi.bonus');
        Route::get('get-user-level-roi-bonus-report', [AdminReportController::class, 'getUserTradeProfitBonus'])->name('user.level.roi.bonus.data');
        Route::get('user-level-roi-bonus/export', [AdminReportController::class, 'exportUserTradeProfitBonus'])->name('user.level.roi.bonus.export');

        Route::get('user-reward-bonus', [AdminReportController::class, 'userRewardBonus'])->name('admin.reports.user.reward.bonus');
        Route::get('get-user-reward-bonus-report', [AdminReportController::class, 'getUserRewardBonus'])->name('user.reward.bonus.data');
        Route::get('user-reward-bonus/export', [AdminReportController::class, 'exportUserRewardBonus'])->name('user.reward.bonus.export');

        Route::get('user-direct-bonus', [AdminReportController::class, 'userDirectBonus'])->name('user.direct.bonus');
        Route::get('get-user-direct-bonus-report', [AdminReportController::class, 'getUserDirectBonus'])->name('user.direct.bonus.data');
        Route::get('user-direct-bonus/export', [AdminReportController::class, 'exportUserDirectBonus'])->name('user.direct.bonus.export');

        Route::get('user-rank-income', [AdminReportController::class, 'userRankIncome'])->name('user.rank.income');
        Route::get('get-user-rank-income-report', [AdminReportController::class, 'getUserRankIncome'])->name('user.rank.income.data');
        Route::get('user-rank-income/export', [AdminReportController::class, 'exportUserRankIncome'])->name('user.rank.income.export');


    });


    Route::prefix('fund-requests')->name('fund.requests.')->group(function () {
        Route::get('/', [AdminFundRequestController::class, 'index'])->name('index');
        Route::post('{request}/accept', [AdminFundRequestController::class, 'accept'])->name('accept');
        Route::post('{request}/reject', [AdminFundRequestController::class, 'reject'])->name('reject');
        Route::get('{request}/download', [AdminFundRequestController::class, 'download'])->name('download');
    });

    Route::prefix('site-settings')->name('site.settings.')->group(function () {
        Route::get('/', [SiteSettingController::class, 'index'])->name('index');
        Route::post('/general', [SiteSettingController::class, 'updateGeneral'])->name('update.general');
        Route::post('/branding', [SiteSettingController::class, 'updateBranding'])->name('update.branding');
        Route::post('/commission', [SiteSettingController::class, 'updateCommission'])->name('update.commission');
        Route::post('/system', [SiteSettingController::class, 'updateSystem'])->name('update.system');
        Route::post('update-email', [SiteSettingController::class, 'updateEmail'])->name('update.email');
        Route::get('email-preview', [SiteSettingController::class, 'previewWelcomeEmail'])->name('email.preview');
        Route::post('/deposit', [SiteSettingController::class, 'updateDeposit'])->name('update.deposit');
        Route::post('/withdrawal', [SiteSettingController::class, 'updateWithdrawal'])->name('update.withdrawal');
        Route::post('/update-telegram', [SiteSettingController::class, 'updateTelegram'])->name('update.telegram');

    });
    Route::prefix('ranks')->name('ranks.')->group(function () {
        Route::get('/rank-rules', [RankRuleController::class, 'index'])->name('index');
        Route::post('/rank-rules', [RankRuleController::class, 'store'])->name('store');
    });
    Route::prefix('plans')->name('plans.')->group(function () {
        Route::get('/', [PlanController::class, 'index'])->name('index');
        Route::get('/create', [PlanController::class, 'create'])->name('create');
        Route::post('/add-plans', [PlanController::class, 'store'])->name('store');

        Route::get('/{plan}/edit', [PlanController::class, 'edit'])->name('edit');
        Route::put('/{plan}', [PlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [PlanController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('scheduled')->name('scheduled.')->group(function () {
        Route::get('/jobs', [ScheduledJobController::class, 'index'])->name('jobs.index');
        Route::post('/jobs', [ScheduledJobController::class, 'store'])->name('jobs.store');
        Route::patch('/jobs/{scheduledJob}/toggle', [ScheduledJobController::class, 'toggle'])->name('jobs.toggle');

        Route::post('/jobs/{scheduledJob}/run-now', [ScheduledJobController::class, 'runNow'])->name('jobs.run-now');
        Route::get('/jobs/{scheduledJob}/logs', [ScheduledJobController::class, 'logs'])->name('jobs.logs');
    });

    Route::prefix('currencies')->name('currencies.')->group(function () {
        Route::get('/', [CurrencyController::class, 'index'])->name('index');
        Route::post('/', [CurrencyController::class, 'store'])->name('store');
        Route::patch('{currency}/toggle', [CurrencyController::class, 'toggle'])->name('toggle');
    });

    Route::prefix('exchange-rates')->name('exchange-rates.')->group(function () {
        Route::get('/', [ExchangeRateController::class, 'index'])->name('index');
        Route::post('/', [ExchangeRateController::class, 'store'])->name('store');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::post('{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
    });

});
