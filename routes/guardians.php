<?php

use App\Http\Controllers\Mobile\Guardian\GuardianAuthController;
use App\Http\Controllers\Mobile\Guardian\HomeController;
use App\Http\Controllers\Mobile\Student\StudentAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register',[GuardianAuthController::class, 'register']);
Route::post('verify-phone',[GuardianAuthController::class, 'verifyPhone']);
Route::post('login',[GuardianAuthController::class, 'login']);
Route::post('reset-password',[GuardianAuthController::class, 'resetPassword']);


Route::group(['middleware' => 'auth:guardian'], function () {
    Route::post('logout',[GuardianAuthController::class, 'logout']);

    Route::get('children',[HomeController::class,'getChildren']);
});
