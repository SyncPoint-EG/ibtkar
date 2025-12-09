<?php

use App\Http\Controllers\Mobile\Guardian\GuardianAuthController;
use App\Http\Controllers\Mobile\Guardian\HomeController;
use Illuminate\Support\Facades\Route;

Route::post('register', [GuardianAuthController::class, 'register']);
Route::post('verify-phone', [GuardianAuthController::class, 'verifyPhone']);
Route::post('login', [GuardianAuthController::class, 'login']);
Route::post('reset-password', [GuardianAuthController::class, 'resetPassword']);

Route::group(['middleware' => 'auth:guardian'], function () {
    Route::post('logout', [GuardianAuthController::class, 'logout']);

    Route::get('children', [HomeController::class, 'getChildren']);
    Route::get('children/{student}/wallet', [HomeController::class, 'getChildWallet']);
    Route::get('children/{student}/charges', [HomeController::class, 'getChildCharges']);
    Route::get('children/{student}/payments', [HomeController::class, 'getChildPayments']);
    Route::get('children/{student}/points', [HomeController::class, 'getChildPoints']);
});
