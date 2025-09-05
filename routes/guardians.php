<?php

use App\Http\Controllers\Mobile\Guardian\GuardianAuthController;
use App\Http\Controllers\Mobile\Guardian\HomeController;
use App\Http\Controllers\Mobile\Student\StudentAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('register',[GuardianAuthController::class, 'register']);
Route::get('verify-phone',[GuardianAuthController::class, 'verifyPhone']);
Route::get('login',[GuardianAuthController::class, 'login']);
Route::get('reset-password',[GuardianAuthController::class, 'resetPassword']);
Route::get('logout',[GuardianAuthController::class, 'logout']);


Route::group(['middleware' => 'auth:guardian'], function () {
    Route::get('children',[HomeController::class,'getChildren']);
});
