<?php

use App\Http\Controllers\Mobile\Student\CourseController;
use App\Http\Controllers\Mobile\Student\HomeController;
use App\Http\Controllers\Mobile\Student\ProfileController;
use App\Http\Controllers\Mobile\Student\StudentAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register',[StudentAuthController::class, 'register']);
Route::post('verify-phone',[StudentAuthController::class, 'verifyPhone']);
Route::post('login',[StudentAuthController::class, 'login']);
Route::post('reset-password',[StudentAuthController::class, 'resetPassword']);
Route::post('logout',[StudentAuthController::class, 'logout']);



Route::group(['middleware' => 'auth:student'], function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('profile', [ProfileController::class, 'update']);

    Route::get('banners', [HomeController::class, 'getBanners']);

    Route::get('subjects',[HomeController::class,'getSubjects']);
    Route::get('subject/{subject}',[HomeController::class,'getSubject']);

    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/{course}', [CourseController::class, 'show']);
});