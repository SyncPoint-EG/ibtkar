<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('stages',[HomeController::class, 'getStages']);
Route::get('grades/{id}',[HomeController::class, 'getGrades']);
Route::get('divisions/{stage}/{grade}',[HomeController::class, 'getDivisions']);
Route::get('centers',[HomeController::class, 'getCenters']);
Route::get('education-types',[HomeController::class, 'getEducationTypes']);
Route::get('governorates',[HomeController::class, 'getGovernorates']);
Route::get('districts/{governorate}',[HomeController::class, 'getDistricts']);



Route::get('courses',[HomeController::class, 'getCourses']);
Route::get('teachers',[HomeController::class, 'getTeachers']);
Route::get('attachments',[HomeController::class, 'getAttachments']);
