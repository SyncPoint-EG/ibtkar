<?php

use App\Http\Controllers\Mobile\Student\AttachmentController;
use App\Http\Controllers\Mobile\Student\CenterExamController;
use App\Http\Controllers\Mobile\Student\CourseController;
use App\Http\Controllers\Mobile\Student\ExamController;
use App\Http\Controllers\Mobile\Student\FavoritesController;
use App\Http\Controllers\Mobile\Student\HomeController;
use App\Http\Controllers\Mobile\Student\HomeworkController;
use App\Http\Controllers\Mobile\Student\InquiryController;
use App\Http\Controllers\Mobile\Student\LessonController;
use App\Http\Controllers\Mobile\Student\LuckWheelController;
use App\Http\Controllers\Mobile\Student\PaymentController;
use App\Http\Controllers\Mobile\Student\ProfileController;
use App\Http\Controllers\Mobile\Student\PurchasedLessonsController;
use App\Http\Controllers\Mobile\Student\RewardController;
use App\Http\Controllers\Mobile\Student\StudentAuthController;
use App\Http\Controllers\Mobile\Student\TableController;
use App\Http\Controllers\Mobile\Student\TeacherController;
use Illuminate\Support\Facades\Route;

Route::post('register', [StudentAuthController::class, 'register']);
Route::post('verify-phone/{id}', [StudentAuthController::class, 'verifyPhone']);
Route::post('login', [StudentAuthController::class, 'login']);
Route::post('reset-password', [StudentAuthController::class, 'resetPassword']);
Route::post('logout', [StudentAuthController::class, 'logout']);

Route::group(['middleware' => 'auth:student'], function () {
    // purchase routes
    Route::post('purchase', [PaymentController::class, 'store']);
    Route::post('charge-wallet', [PaymentController::class, 'chargeWallet']);
    Route::get('general-plan-price', [HomeController::class, 'getPlanPrice']);

    // delete account
    Route::post('delete-account', [StudentAuthController::class, 'deleteAccount']);
    // profile routes
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('profile', [ProfileController::class, 'update']);
    Route::post('update-image', [ProfileController::class, 'updateImage']);

    Route::get('student-points', [ProfileController::class, 'studentPoints']);
    Route::get('students-by-points', [ProfileController::class, 'studentsByPoints']);

    Route::post('delete-account', [ProfileController::class, 'deleteAccount']);

    Route::get('banners', [HomeController::class, 'getBanners']);

    Route::get('subjects', [HomeController::class, 'getSubjects']);
    Route::get('subject/{subject}', [HomeController::class, 'getSubject']);
    Route::get('subject/{subject}/teacher', [TableController::class, 'getSubjectTeacher']);

    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/{course}', [CourseController::class, 'show']);

    // lessons routes
    Route::get('lessons', [LessonController::class, 'getLessons']);
    Route::get('lesson/{lesson}', [LessonController::class, 'getLesson']);
    Route::post('lesson-watch/{lesson}', [LessonController::class, 'watch']);

    // lessons attachment routes
    Route::get('attachments', [AttachmentController::class, 'allAttachments']);

    // teachers routes
    Route::get('teachers', [TeacherController::class, 'index']);
    Route::get('teachers-stories', [TeacherController::class, 'teacherStories']);
    Route::get('teacher/{teacher}', [TeacherController::class, 'show']);
    Route::get('teacher/{teacher}/timeline', [TeacherController::class, 'timeline']);
    Route::get('teacher/{teacher}/lessons-by-subject', [TeacherController::class, 'lessonsBySubject']);

    // selects routes
    Route::get('divisions', [HomeController::class, 'getDivisions']);
    Route::get('stages', [HomeController::class, 'getStages']);
    Route::get('grades', [HomeController::class, 'getGrades']);
    Route::get('timeline', [HomeController::class, 'timeline']);

    // luck wheel items
    Route::get('luck-wheel', [LuckWheelController::class, 'index']);
    Route::post('luck-wheel/spin', [LuckWheelController::class, 'spin']);
    Route::get('check-spin', [LuckWheelController::class, 'checkSpin']);

    // exams routes
    Route::get('exams', [ExamController::class, 'index']);
    Route::get('exam/{exam}', [ExamController::class, 'show']);
    Route::post('exam/{exam}/submit', [ExamController::class, 'submit']);

    // homeworks routes
    Route::get('homeworks', [HomeworkController::class, 'index']);
    Route::get('homework/{homework}', [HomeworkController::class, 'show']);
    Route::post('homework/{homework}/submit', [HomeworkController::class, 'submit']);

    // center exams routes
    Route::get('center-exams', [CenterExamController::class, 'index']);
    Route::get('center-exam/{centerExam}', [CenterExamController::class, 'show']);
    Route::post('center-exam/{centerExam}/submit', [CenterExamController::class, 'submit']);

    // purchased lessons
    Route::get('my-lessons', [PurchasedLessonsController::class, 'index']);

    // favorites routes
    Route::get('favorite', [FavoritesController::class, 'listFavorites']);
    Route::post('add-to-favorite', [FavoritesController::class, 'addToFavorite']);
    Route::post('remove-from-favorite', [FavoritesController::class, 'removeFromFavorite']);

    // tables routes
    Route::get('general-table', [TableController::class, 'getGeneralTeacherTables']);
    Route::get('private-table', [TableController::class, 'getPrivateTable']);

    // rewards routes
    Route::get('rewards', [RewardController::class, 'index']);
    Route::post('rewards/{id}/purchase', [RewardController::class, 'purchase']);
    Route::get('rewards-history', [RewardController::class, 'rewardsHistory']);
    Route::get('action-points', [RewardController::class, 'actionPoints']);

    // inquiries routes
    Route::get('inquiries', [InquiryController::class, 'index']);
    Route::get('inquiries/subjects', [InquiryController::class, 'subjects']);
    Route::post('inquiries', [InquiryController::class, 'store']);

    // point redemptions
    Route::get('point-redemptions', [\App\Http\Controllers\Mobile\Student\PointRedemptionController::class, 'index']);
    Route::post('point-redemptions/{pointRedemption}/redeem', [\App\Http\Controllers\Mobile\Student\PointRedemptionController::class, 'redeem']);
});
