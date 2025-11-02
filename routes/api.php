<?php

use App\Http\Controllers\Api\CodeImportController;
use App\Http\Controllers\Api\TestNotificationController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::put('test/notification', TestNotificationController::class);

Route::get('stages', [HomeController::class, 'getStages']);
Route::get('grades/{id}', [HomeController::class, 'getGrades']);
Route::get('divisions/{stage}/{grade}', [HomeController::class, 'getDivisions']);
Route::get('centers', [HomeController::class, 'getCenters']);
Route::get('education-types', [HomeController::class, 'getEducationTypes']);
Route::get('governorates', [HomeController::class, 'getGovernorates']);
Route::get('districts/{governorate}', [HomeController::class, 'getDistricts']);

Route::get('courses', [HomeController::class, 'getCourses']);
Route::get('teachers', [HomeController::class, 'getTeachers']);
Route::get('attachments', [HomeController::class, 'getAttachments']);
// Route::get('student/tables', [App\Http\Controllers\Mobile\Student\TableController::class, 'getTeacherTables'])->middleware('auth:sanctum');

Route::post('codes/import-price', CodeImportController::class);


Route::group(['prefix' => 'notifications', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    Route::get('/unread', [\App\Http\Controllers\Api\NotificationController::class, 'unread']);
    Route::get('/read', [\App\Http\Controllers\Api\NotificationController::class, 'read']);
    Route::get('/count', [\App\Http\Controllers\Api\NotificationController::class, 'count']);
    Route::get('/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
    Route::get('/read-count', [\App\Http\Controllers\Api\NotificationController::class, 'readCount']);
    Route::get('/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'show']);
    Route::post('/{id}/mark-as-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
    Route::post('/mark-all-as-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'destroy']);
    Route::delete('/', [\App\Http\Controllers\Api\NotificationController::class, 'destroyAll']);
});
