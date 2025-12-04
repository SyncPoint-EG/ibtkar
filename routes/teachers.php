<?php

use App\Http\Controllers\Mobile\Teacher\HomeController;
use App\Http\Controllers\Mobile\Teacher\StatisticsController;
use App\Http\Controllers\Mobile\Teacher\StoryController;
use App\Http\Controllers\Mobile\Teacher\TeacherAuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [TeacherAuthController::class, 'login']);
Route::middleware('auth:teacher')->group(function () {
    Route::post('logout', [TeacherAuthController::class, 'logout']);

    Route::post('stories', [StoryController::class, 'store']);
    Route::get('stories', [StoryController::class, 'index']);
    Route::get('statistics', [StatisticsController::class, 'index']);
    Route::get('exams', [HomeController::class, 'getExams']);
    Route::get('exam-students/{exam_id}', [HomeController::class, 'getExamStudents']);
    Route::get('homework', [HomeController::class, 'getHomeworks']);
    Route::get('homework-students/{homework_id}', [HomeController::class, 'getHomeworkStudents']);
    Route::get('attachments', [HomeController::class, 'getAttachments']);
    Route::get('lessons', [HomeController::class, 'getLessons']);
    Route::get('stages', [HomeController::class, 'getStages']);
    Route::get('grades', [HomeController::class, 'getGrades']);

    Route::get('students', [HomeController::class, 'getStudents']);
    Route::get('student/{id}', [HomeController::class, 'getStudent']);
    Route::get('students-per-lesson/{lesson_id}', [StatisticsController::class, 'getStudentsPerLesson']);
});
