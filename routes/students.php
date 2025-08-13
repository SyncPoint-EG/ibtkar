<?php

use App\Http\Controllers\Mobile\Student\CourseController;
use App\Http\Controllers\Mobile\Student\HomeController;
use App\Http\Controllers\Mobile\Student\LessonController;
use App\Http\Controllers\Mobile\Student\LuckWheelController;
use App\Http\Controllers\Mobile\Student\PaymentController;
use App\Http\Controllers\Mobile\Student\ProfileController;
use App\Http\Controllers\Mobile\Student\StudentAuthController;
use App\Http\Controllers\Mobile\Student\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register',[StudentAuthController::class, 'register']);
Route::post('verify-phone/{id}',[StudentAuthController::class, 'verifyPhone']);
Route::post('login',[StudentAuthController::class, 'login']);
Route::post('reset-password',[StudentAuthController::class, 'resetPassword']);
Route::post('logout',[StudentAuthController::class, 'logout']);



Route::group(['middleware' => 'auth:student'], function () {
    // purchase routes
    Route::post('purchase',[PaymentController::class, 'store']);

    // profile routes
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('profile', [ProfileController::class, 'update']);

    Route::get('banners', [HomeController::class, 'getBanners']);

    Route::get('subjects',[HomeController::class,'getSubjects']);
    Route::get('subject/{subject}',[HomeController::class,'getSubject']);

    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/{course}', [CourseController::class, 'show']);

    //lessons routes
    Route::get('lessons', [LessonController::class, 'getLessons']);
    Route::get('lesson/{lesson}', [LessonController::class, 'getLesson']);

    //teachers routes
    Route::get('teachers', [TeacherController::class, 'index']);
    Route::get('teacher/{teacher}', [TeacherController::class, 'show']);

    // selects routes
    Route::get('divisions',[HomeController::class,'getDivisions']);
    Route::get('stages',[HomeController::class,'getStages']);
    Route::get('grades',[HomeController::class,'getGrades']);

    // luck wheel items
    Route::get('luck-wheel',[LuckWheelController::class, 'index']);
});
