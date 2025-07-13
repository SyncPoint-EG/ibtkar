<?php

use App\Http\Controllers\Mobile\Student\StudentAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('register',[StudentAuthController::class, 'register']);
Route::get('verify-phone',[StudentAuthController::class, 'verifyPhone']);
Route::get('login',[StudentAuthController::class, 'login']);
Route::get('reset-password',[StudentAuthController::class, 'resetPassword']);
Route::get('logout',[StudentAuthController::class, 'logout']);
