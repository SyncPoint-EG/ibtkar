<?php

use App\Http\Controllers\Mobile\Teacher\HomeController;
use App\Http\Controllers\Mobile\Teacher\StatisticsController;
use App\Http\Controllers\Mobile\Teacher\StoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:teacher')->group(function () {
    Route::post('stories', [StoryController::class, 'store']);
    Route::get('stories', [StoryController::class, 'index']);
    Route::get('statistics', [StatisticsController::class, 'index']);
    Route::get('exams' , [HomeController::class,'getExams']);
    Route::get('homework' , [HomeController::class,'getHomeworks']);
    Route::get('attachments' , [HomeController::class,'getAttachments']);
});
