<?php

use App\Models\UserUsdWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Api\RoleController;
use App\Http\Controllers\Admin\Api\PermissionController;
use App\Http\Controllers\Admin\Api\AdminRoleController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::post('admins/{admin}/assign-role', [AdminRoleController::class, 'assignRole']);
});






