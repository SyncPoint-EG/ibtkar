<?php

use App\Http\Controllers\Mobile\Student\StudentAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register',[StudentAuthController::class, 'register']);
Route::post('verify-phone',[StudentAuthController::class, 'verifyPhone']);
Route::post('login',[StudentAuthController::class, 'login']);
Route::post('reset-password',[StudentAuthController::class, 'resetPassword']);
Route::post('logout',[StudentAuthController::class, 'logout']);



Route::group(['middleware' => 'auth:student'], function () {
   Route::get('profile', [StudentAuthController::class, 'profile']);
});
