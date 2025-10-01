<?php

use App\Http\Controllers\Dashboard\CenterExamQuestionController;
use App\Http\Controllers\Dashboard\HomeworkController;
use App\Http\Controllers\Dashboard\HomeworkQuestionController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\GamificationController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\CenterExamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\PostController;
use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\Dashboard\TeamController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\GovernorateController;
use App\Http\Controllers\Dashboard\DistrictController;
use App\Http\Controllers\Dashboard\CenterController;
use App\Http\Controllers\Dashboard\StageController;
use App\Http\Controllers\Dashboard\GradeController;
use App\Http\Controllers\Dashboard\DivisionController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\Dashboard\GuardianController;
use App\Http\Controllers\Dashboard\SubjectController;
use App\Http\Controllers\Dashboard\TeacherController;
use App\Http\Controllers\Dashboard\EducationTypeController;
use App\Http\Controllers\Dashboard\SemisterController;
use App\Http\Controllers\Dashboard\CourseController;
use App\Http\Controllers\Dashboard\ChapterController;
use App\Http\Controllers\Dashboard\LessonController;
use App\Http\Controllers\Dashboard\LessonAttachmentController;
use App\Http\Controllers\Dashboard\ExamController;
use App\Http\Controllers\Dashboard\BannerController;
use App\Http\Controllers\Dashboard\CodeController;
use App\Http\Controllers\Dashboard\PaymentApprovalController;
use App\Http\Controllers\Dashboard\ChargeApprovalController;
use App\Http\Controllers\Dashboard\HomeController as DashboardHomeController;


 Route::get('/index', [\App\Http\Controllers\Dashboard\HomeController::class,'index'])->name('dashboard')->middleware('auth');

//Route::get('/', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');


// Authentication Routes
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/language/{locale}', [\App\Http\Controllers\HomeController::class, 'switchLanguage'])->name('language.switch');
// Profile Routes
Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile')->middleware('auth');
Route::put('/profile', [App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'showForgotPasswordForm'])->name('password.request')->middleware('guest');
Route::get('/reset-password', [App\Http\Controllers\AuthController::class, 'showResetPasswordForm'])->name('password.reset')->middleware('guest');



// Profile routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
//    Route::get('/statistics', function () {
//
//        return view('dashboard.temp.index');
//    })->name('dashboard');
//    Route::get('/dashboard', [DashboardHomeController::class, 'index'])->name('dashboard');
    // Profile edit page
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Update profile
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Delete profile image
    Route::delete('/profile/delete-image', [ProfileController::class, 'deleteImage'])->name('profile.delete-image');

    // Logout route
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');

    // Routes for Payment Approvals
    Route::get('payment-approvals', [PaymentApprovalController::class, 'index'])
        ->name('payment_approvals.index')
        ->middleware('can:view_payment_approval');

    Route::post('payment-approvals/{payment}/accept', [PaymentApprovalController::class, 'accept'])
        ->name('payment_approvals.accept')
        ->middleware('can:accept_payment_approval');

    Route::post('payment-approvals/{payment}/reject', [PaymentApprovalController::class, 'reject'])
        ->name('payment_approvals.reject')
        ->middleware('can:reject_payment_approval');

    // Routes for Charge Approvals
    Route::get('charge-approvals', [ChargeApprovalController::class, 'index'])
        ->name('charge_approvals.index')
        ->middleware('can:view_charge_approval');

    Route::post('charge-approvals/{charge}/accept', [ChargeApprovalController::class, 'accept'])
        ->name('charge_approvals.accept')
        ->middleware('can:accept_charge_approval');

    Route::post('charge-approvals/{charge}/reject', [ChargeApprovalController::class, 'reject'])
        ->name('charge_approvals.reject')
        ->middleware('can:reject_charge_approval');

    // Routes for Center Exams
    Route::get('center-exams', [CenterExamController::class, 'index'])
        ->name('center-exams.index')
        ->middleware('can:view_center_exam');

    Route::get('center-exams/create', [CenterExamController::class, 'create'])
        ->name('center-exams.create')
        ->middleware('can:create_center_exam');

    Route::post('center-exams', [CenterExamController::class, 'store'])
        ->name('center-exams.store')
        ->middleware('can:create_center_exam');

    Route::get('center-exams/{centerExam}', [CenterExamController::class, 'show'])
        ->name('center-exams.show')
        ->middleware('can:view_center_exam');

    Route::get('center-exams/{centerExam}/edit', [CenterExamController::class, 'edit'])
        ->name('center-exams.edit')
        ->middleware('can:edit_center_exam');

    Route::put('center-exams/{centerExam}', [CenterExamController::class, 'update'])
        ->name('center-exams.update')
        ->middleware('can:edit_center_exam');

    Route::delete('center-exams/{centerExam}', [CenterExamController::class, 'destroy'])
        ->name('center-exams.destroy')
        ->middleware('can:delete_center_exam');

    Route::get('center-exams/{centerExam}/submissions', [CenterExamController::class, 'submissions'])
        ->name('center-exams.submissions')
        ->middleware('can:view_center_exam');

});











// Routes for Role
Route::middleware(['auth'])->group(function() {
    Route::get('roles', [RoleController::class, 'index'])
        ->name('roles.index')
        ->middleware('can:view_role');

    Route::get('roles/create', [RoleController::class, 'create'])
        ->name('roles.create')
        ->middleware('can:create_role');

    Route::post('roles', [RoleController::class, 'store'])
        ->name('roles.store')
        ->middleware('can:create_role');

    Route::get('roles/{role}', [RoleController::class, 'show'])
        ->name('roles.show')
        ->middleware('can:view_role');

    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])
        ->name('roles.edit')
        ->middleware('can:edit_role');

    Route::put('roles/{role}', [RoleController::class, 'update'])
        ->name('roles.update')
        ->middleware('can:edit_role');

    Route::delete('roles/{role}', [RoleController::class, 'destroy'])
        ->name('roles.destroy')
        ->middleware('can:delete_role');
});

// Routes for Permission
Route::middleware(['auth'])->group(function() {
    Route::get('permissions', [PermissionController::class, 'index'])
        ->name('permissions.index')
        ->middleware('can:view_permission');

    Route::get('permissions/create', [PermissionController::class, 'create'])
        ->name('permissions.create')
        ->middleware('can:create_permission');

    Route::post('permissions', [PermissionController::class, 'store'])
        ->name('permissions.store')
        ->middleware('can:create_permission');

    Route::get('permissions/{permission}', [PermissionController::class, 'show'])
        ->name('permissions.show')
        ->middleware('can:view_permission');

    Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])
        ->name('permissions.edit')
        ->middleware('can:edit_permission');

    Route::put('permissions/{permission}', [PermissionController::class, 'update'])
        ->name('permissions.update')
        ->middleware('can:edit_permission');

    Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])
        ->name('permissions.destroy')
        ->middleware('can:delete_permission');
});



// Routes for User
Route::middleware(['auth'])->group(function() {
    Route::get('users', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware('can:view_user');

    Route::get('users/create', [UserController::class, 'create'])
        ->name('users.create')
        ->middleware('can:create_user');

    Route::post('users', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('can:create_user');

    Route::get('users/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware('can:view_user');

    Route::get('users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('can:edit_user');

    Route::put('users/{user}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('can:edit_user');

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('can:delete_user');
});



// Routes for Governorate
Route::middleware(['auth'])->group(function() {
    Route::get('governorates', [GovernorateController::class, 'index'])
        ->name('governorates.index')
        ->middleware('can:view_governorate');

    Route::get('governorates/create', [GovernorateController::class, 'create'])
        ->name('governorates.create')
        ->middleware('can:create_governorate');

    Route::post('governorates', [GovernorateController::class, 'store'])
        ->name('governorates.store')
        ->middleware('can:create_governorate');

    Route::get('governorates/{governorate}', [GovernorateController::class, 'show'])
        ->name('governorates.show')
        ->middleware('can:view_governorate');

    Route::get('governorates/{governorate}/edit', [GovernorateController::class, 'edit'])
        ->name('governorates.edit')
        ->middleware('can:edit_governorate');

    Route::put('governorates/{governorate}', [GovernorateController::class, 'update'])
        ->name('governorates.update')
        ->middleware('can:edit_governorate');

    Route::delete('governorates/{governorate}', [GovernorateController::class, 'destroy'])
        ->name('governorates.destroy')
        ->middleware('can:delete_governorate');
});

// Routes for Governorate
Route::middleware(['auth'])->group(function() {
    Route::get('governorates', [GovernorateController::class, 'index'])
        ->name('governorates.index')
        ->middleware('can:view_governorate');

    Route::get('governorates/create', [GovernorateController::class, 'create'])
        ->name('governorates.create')
        ->middleware('can:create_governorate');

    Route::post('governorates', [GovernorateController::class, 'store'])
        ->name('governorates.store')
        ->middleware('can:create_governorate');

    Route::get('governorates/{governorate}', [GovernorateController::class, 'show'])
        ->name('governorates.show')
        ->middleware('can:view_governorate');

    Route::get('governorates/{governorate}/edit', [GovernorateController::class, 'edit'])
        ->name('governorates.edit')
        ->middleware('can:edit_governorate');

    Route::put('governorates/{governorate}', [GovernorateController::class, 'update'])
        ->name('governorates.update')
        ->middleware('can:edit_governorate');

    Route::delete('governorates/{governorate}', [GovernorateController::class, 'destroy'])
        ->name('governorates.destroy')
        ->middleware('can:delete_governorate');
});

// Routes for Governorate
Route::middleware(['auth'])->group(function() {
    Route::get('governorates', [GovernorateController::class, 'index'])
        ->name('governorates.index')
        ->middleware('can:view_governorate');

    Route::get('governorates/create', [GovernorateController::class, 'create'])
        ->name('governorates.create')
        ->middleware('can:create_governorate');

    Route::post('governorates', [GovernorateController::class, 'store'])
        ->name('governorates.store')
        ->middleware('can:create_governorate');

    Route::get('governorates/{governorate}', [GovernorateController::class, 'show'])
        ->name('governorates.show')
        ->middleware('can:view_governorate');

    Route::get('governorates/{governorate}/edit', [GovernorateController::class, 'edit'])
        ->name('governorates.edit')
        ->middleware('can:edit_governorate');

    Route::put('governorates/{governorate}', [GovernorateController::class, 'update'])
        ->name('governorates.update')
        ->middleware('can:edit_governorate');

    Route::delete('governorates/{governorate}', [GovernorateController::class, 'destroy'])
        ->name('governorates.destroy')
        ->middleware('can:delete_governorate');
});

// Routes for District
Route::middleware(['auth'])->group(function() {
    Route::get('districts', [DistrictController::class, 'index'])
        ->name('districts.index')
        ->middleware('can:view_district');

    Route::get('districts/create', [DistrictController::class, 'create'])
        ->name('districts.create')
        ->middleware('can:create_district');

    Route::post('districts', [DistrictController::class, 'store'])
        ->name('districts.store')
        ->middleware('can:create_district');

    Route::get('districts/{district}', [DistrictController::class, 'show'])
        ->name('districts.show')
        ->middleware('can:view_district');

    Route::get('districts/{district}/edit', [DistrictController::class, 'edit'])
        ->name('districts.edit')
        ->middleware('can:edit_district');

    Route::put('districts/{district}', [DistrictController::class, 'update'])
        ->name('districts.update')
        ->middleware('can:edit_district');

    Route::delete('districts/{district}', [DistrictController::class, 'destroy'])
        ->name('districts.destroy')
        ->middleware('can:delete_district');
});

// Routes for Center
Route::middleware(['auth'])->group(function() {
    Route::get('centers', [CenterController::class, 'index'])
        ->name('centers.index')
        ->middleware('can:view_center');

    Route::get('centers/create', [CenterController::class, 'create'])
        ->name('centers.create')
        ->middleware('can:create_center');

    Route::post('centers', [CenterController::class, 'store'])
        ->name('centers.store')
        ->middleware('can:create_center');

    Route::get('centers/{center}', [CenterController::class, 'show'])
        ->name('centers.show')
        ->middleware('can:view_center');

    Route::get('centers/{center}/edit', [CenterController::class, 'edit'])
        ->name('centers.edit')
        ->middleware('can:edit_center');

    Route::put('centers/{center}', [CenterController::class, 'update'])
        ->name('centers.update')
        ->middleware('can:edit_center');

    Route::delete('centers/{center}', [CenterController::class, 'destroy'])
        ->name('centers.destroy')
        ->middleware('can:delete_center');
});

// Routes for Stage
Route::middleware(['auth'])->group(function() {
    Route::get('stages', [StageController::class, 'index'])
        ->name('stages.index')
        ->middleware('can:view_stage');

    Route::get('stages/create', [StageController::class, 'create'])
        ->name('stages.create')
        ->middleware('can:create_stage');

    Route::post('stages', [StageController::class, 'store'])
        ->name('stages.store')
        ->middleware('can:create_stage');

    Route::get('stages/{stage}', [StageController::class, 'show'])
        ->name('stages.show')
        ->middleware('can:view_stage');

    Route::get('stages/{stage}/edit', [StageController::class, 'edit'])
        ->name('stages.edit')
        ->middleware('can:edit_stage');

    Route::put('stages/{stage}', [StageController::class, 'update'])
        ->name('stages.update')
        ->middleware('can:edit_stage');

    Route::delete('stages/{stage}', [StageController::class, 'destroy'])
        ->name('stages.destroy')
        ->middleware('can:delete_stage');
});

// Routes for Grade
Route::middleware(['auth'])->group(function() {
    Route::get('grades', [GradeController::class, 'index'])
        ->name('grades.index')
        ->middleware('can:view_grade');

    Route::get('grades/create', [GradeController::class, 'create'])
        ->name('grades.create')
        ->middleware('can:create_grade');

    Route::post('grades', [GradeController::class, 'store'])
        ->name('grades.store')
        ->middleware('can:create_grade');

    Route::get('grades/{grade}', [GradeController::class, 'show'])
        ->name('grades.show')
        ->middleware('can:view_grade');

    Route::get('grades/{grade}/edit', [GradeController::class, 'edit'])
        ->name('grades.edit')
        ->middleware('can:edit_grade');

    Route::put('grades/{grade}', [GradeController::class, 'update'])
        ->name('grades.update')
        ->middleware('can:edit_grade');

    Route::delete('grades/{grade}', [GradeController::class, 'destroy'])
        ->name('grades.destroy')
        ->middleware('can:delete_grade');
});

// Routes for Grade
Route::middleware(['auth'])->group(function() {
    Route::get('grades', [GradeController::class, 'index'])
        ->name('grades.index')
        ->middleware('can:view_grade');

    Route::get('grades/create', [GradeController::class, 'create'])
        ->name('grades.create')
        ->middleware('can:create_grade');

    Route::post('grades', [GradeController::class, 'store'])
        ->name('grades.store')
        ->middleware('can:create_grade');

    Route::get('grades/{grade}', [GradeController::class, 'show'])
        ->name('grades.show')
        ->middleware('can:view_grade');

    Route::get('grades/{grade}/edit', [GradeController::class, 'edit'])
        ->name('grades.edit')
        ->middleware('can:edit_grade');

    Route::put('grades/{grade}', [GradeController::class, 'update'])
        ->name('grades.update')
        ->middleware('can:edit_grade');

    Route::delete('grades/{grade}', [GradeController::class, 'destroy'])
        ->name('grades.destroy')
        ->middleware('can:delete_grade');
});

// Routes for District
Route::middleware(['auth'])->group(function() {
    Route::get('districts', [DistrictController::class, 'index'])
        ->name('districts.index')
        ->middleware('can:view_district');

    Route::get('districts/create', [DistrictController::class, 'create'])
        ->name('districts.create')
        ->middleware('can:create_district');

    Route::post('districts', [DistrictController::class, 'store'])
        ->name('districts.store')
        ->middleware('can:create_district');

    Route::get('districts/{district}', [DistrictController::class, 'show'])
        ->name('districts.show')
        ->middleware('can:view_district');

    Route::get('districts/{district}/edit', [DistrictController::class, 'edit'])
        ->name('districts.edit')
        ->middleware('can:edit_district');

    Route::put('districts/{district}', [DistrictController::class, 'update'])
        ->name('districts.update')
        ->middleware('can:edit_district');

    Route::delete('districts/{district}', [DistrictController::class, 'destroy'])
        ->name('districts.destroy')
        ->middleware('can:delete_district');
});

// Routes for Grade
Route::middleware(['auth'])->group(function() {
    Route::get('grades', [GradeController::class, 'index'])
        ->name('grades.index')
        ->middleware('can:view_grade');

    Route::get('grades/create', [GradeController::class, 'create'])
        ->name('grades.create')
        ->middleware('can:create_grade');

    Route::post('grades', [GradeController::class, 'store'])
        ->name('grades.store')
        ->middleware('can:create_grade');

    Route::get('grades/{grade}', [GradeController::class, 'show'])
        ->name('grades.show')
        ->middleware('can:view_grade');

    Route::get('grades/{grade}/edit', [GradeController::class, 'edit'])
        ->name('grades.edit')
        ->middleware('can:edit_grade');

    Route::put('grades/{grade}', [GradeController::class, 'update'])
        ->name('grades.update')
        ->middleware('can:edit_grade');

    Route::delete('grades/{grade}', [GradeController::class, 'destroy'])
        ->name('grades.destroy')
        ->middleware('can:delete_grade');
});

// Routes for District
Route::middleware(['auth'])->group(function() {
    Route::get('districts', [DistrictController::class, 'index'])
        ->name('districts.index')
        ->middleware('can:view_district');

    Route::get('districts/create', [DistrictController::class, 'create'])
        ->name('districts.create')
        ->middleware('can:create_district');

    Route::post('districts', [DistrictController::class, 'store'])
        ->name('districts.store')
        ->middleware('can:create_district');

    Route::get('districts/{district}', [DistrictController::class, 'show'])
        ->name('districts.show')
        ->middleware('can:view_district');

    Route::get('districts/{district}/edit', [DistrictController::class, 'edit'])
        ->name('districts.edit')
        ->middleware('can:edit_district');

    Route::put('districts/{district}', [DistrictController::class, 'update'])
        ->name('districts.update')
        ->middleware('can:edit_district');

    Route::delete('districts/{district}', [DistrictController::class, 'destroy'])
        ->name('districts.destroy')
        ->middleware('can:delete_district');
});

// Routes for Grade
Route::middleware(['auth'])->group(function() {
    Route::get('grades', [GradeController::class, 'index'])
        ->name('grades.index')
        ->middleware('can:view_grade');

    Route::get('grades/create', [GradeController::class, 'create'])
        ->name('grades.create')
        ->middleware('can:create_grade');

    Route::post('grades', [GradeController::class, 'store'])
        ->name('grades.store')
        ->middleware('can:create_grade');

    Route::get('grades/{grade}', [GradeController::class, 'show'])
        ->name('grades.show')
        ->middleware('can:view_grade');

    Route::get('grades/{grade}/edit', [GradeController::class, 'edit'])
        ->name('grades.edit')
        ->middleware('can:edit_grade');

    Route::put('grades/{grade}', [GradeController::class, 'update'])
        ->name('grades.update')
        ->middleware('can:edit_grade');

    Route::delete('grades/{grade}', [GradeController::class, 'destroy'])
        ->name('grades.destroy')
        ->middleware('can:delete_grade');
});

// Routes for Division
Route::middleware(['auth'])->group(function() {
    Route::get('divisions', [DivisionController::class, 'index'])
        ->name('divisions.index')
        ->middleware('can:view_division');

    Route::get('divisions/create', [DivisionController::class, 'create'])
        ->name('divisions.create')
        ->middleware('can:create_division');

    Route::post('divisions', [DivisionController::class, 'store'])
        ->name('divisions.store')
        ->middleware('can:create_division');

    Route::get('divisions/{division}', [DivisionController::class, 'show'])
        ->name('divisions.show')
        ->middleware('can:view_division');

    Route::get('divisions/{division}/edit', [DivisionController::class, 'edit'])
        ->name('divisions.edit')
        ->middleware('can:edit_division');

    Route::put('divisions/{division}', [DivisionController::class, 'update'])
        ->name('divisions.update')
        ->middleware('can:edit_division');

    Route::delete('divisions/{division}', [DivisionController::class, 'destroy'])
        ->name('divisions.destroy')
        ->middleware('can:delete_division');
});



// Routes for Student
Route::middleware(['auth'])->group(function() {
    Route::get('students', [StudentController::class, 'index'])
        ->name('students.index')
        ->middleware('can:view_student');

    Route::get('students/create', [StudentController::class, 'create'])
        ->name('students.create')
        ->middleware('can:create_student');

    Route::post('students', [StudentController::class, 'store'])
        ->name('students.store')
        ->middleware('can:create_student');

    Route::get('students/{student}', [StudentController::class, 'show'])
        ->name('students.show')
        ->middleware('can:view_student');

    Route::get('students/{student}/edit', [StudentController::class, 'edit'])
        ->name('students.edit')
        ->middleware('can:edit_student');

    Route::put('students/{student}', [StudentController::class, 'update'])
        ->name('students.update')
        ->middleware('can:edit_student');

    Route::delete('students/{student}', [StudentController::class, 'destroy'])
        ->name('students.destroy')
        ->middleware('can:delete_student');

    Route::get('students/export/data', [StudentController::class, 'export'])
        ->name('students.export')
        ->middleware('can:view_student');

    Route::post('students/import', [StudentController::class, 'import'])
        ->name('students.import')
        ->middleware('can:create_student');

    Route::get('students/{student}/export-lessons', [StudentController::class, 'exportLessons'])
        ->name('students.export.lessons')
        ->middleware('can:view_student');
});

// Routes for Guardian
Route::middleware(['auth'])->group(function() {
    Route::get('guardians', [GuardianController::class, 'index'])
        ->name('guardians.index')
        ->middleware('can:view_guardian');

    Route::get('guardians/create', [GuardianController::class, 'create'])
        ->name('guardians.create')
        ->middleware('can:create_guardian');

    Route::post('guardians', [GuardianController::class, 'store'])
        ->name('guardians.store')
        ->middleware('can:create_guardian');

    Route::get('guardians/{guardian}', [GuardianController::class, 'show'])
        ->name('guardians.show')
        ->middleware('can:view_guardian');

    Route::get('guardians/{guardian}/edit', [GuardianController::class, 'edit'])
        ->name('guardians.edit')
        ->middleware('can:edit_guardian');

    Route::put('guardians/{guardian}', [GuardianController::class, 'update'])
        ->name('guardians.update')
        ->middleware('can:edit_guardian');

    Route::delete('guardians/{guardian}', [GuardianController::class, 'destroy'])
        ->name('guardians.destroy')
        ->middleware('can:delete_guardian');
});

// Routes for Subject
Route::middleware(['auth'])->group(function() {
    Route::get('subjects', [SubjectController::class, 'index'])
        ->name('subjects.index')
        ->middleware('can:view_subject');

    Route::get('subjects/create', [SubjectController::class, 'create'])
        ->name('subjects.create')
        ->middleware('can:create_subject');

    Route::post('subjects', [SubjectController::class, 'store'])
        ->name('subjects.store')
        ->middleware('can:create_subject');

    Route::get('subjects/{subject}', [SubjectController::class, 'show'])
        ->name('subjects.show')
        ->middleware('can:view_subject');

    Route::get('subjects/{subject}/edit', [SubjectController::class, 'edit'])
        ->name('subjects.edit')
        ->middleware('can:edit_subject');

    Route::put('subjects/{subject}', [SubjectController::class, 'update'])
        ->name('subjects.update')
        ->middleware('can:edit_subject');

    Route::delete('subjects/{subject}', [SubjectController::class, 'destroy'])
        ->name('subjects.destroy')
        ->middleware('can:delete_subject');
});

// Routes for Teacher
Route::middleware(['auth'])->group(function() {
    Route::get('teachers', [TeacherController::class, 'index'])
        ->name('teachers.index')
        ->middleware('can:view_teacher');

    Route::get('teachers/create', [TeacherController::class, 'create'])
        ->name('teachers.create')
        ->middleware('can:create_teacher');

    Route::post('teachers', [TeacherController::class, 'store'])
        ->name('teachers.store')
        ->middleware('can:create_teacher');

    Route::get('teacher/{teacher}', [TeacherController::class, 'show'])
        ->name('teachers.show')
        ->middleware('can:view_teacher');

    Route::get('teachers/{teacher}/edit', [TeacherController::class, 'edit'])
        ->name('teachers.edit')
        ->middleware('can:edit_teacher');

    Route::put('teachers/{teacher}', [TeacherController::class, 'update'])
        ->name('teachers.update')
        ->middleware('can:edit_teacher');

    Route::delete('teachers/{teacher}', [TeacherController::class, 'destroy'])
        ->name('teachers.destroy')
        ->middleware('can:delete_teacher');

    Route::post('teachers/{teacher}/toggle-activation', [TeacherController::class, 'toggleActivation'])
        ->name('teachers.toggle-activation')
        ->middleware('can:edit_teacher');

    Route::post('teachers/{teacher}/toggle-featured', [TeacherController::class, 'toggleFeatured'])
        ->name('teachers.toggle-featured')
        ->middleware('can:edit_teacher');

    Route::get('teachers/export', [TeacherController::class, 'export'])
        ->name('teachers.export')
        ->middleware('can:view_teacher');

    Route::post('teachers/import', [TeacherController::class, 'import'])
        ->name('teachers.import')
        ->middleware('can:create_teacher');

    // AJAX search route
    Route::get('teachers/search', [TeacherController::class, 'search'])
        ->name('teachers.search')
        ->middleware('can:view_teacher');

    Route::get('teachers/{teacher}/courses', [TeacherController::class, 'getCourses'])
        ->name('teachers.courses')
        ->middleware('can:view_teacher');

    Route::get('teachers/{teacher}/students', [TeacherController::class, 'students'])
        ->name('teachers.students')
        ->middleware('can:view_teacher');

    Route::get('teachers/{teacher}/students/export', [TeacherController::class, 'exportStudents'])
        ->name('teachers.students.export')
        ->middleware('can:view_teacher');
});

// Routes for EducationType
Route::middleware(['auth'])->group(function() {
    Route::get('education-types', [EducationTypeController::class, 'index'])
        ->name('education-types.index')
        ->middleware('can:view_educationtype');

    Route::get('education-types/create', [EducationTypeController::class, 'create'])
        ->name('education-types.create')
        ->middleware('can:create_educationtype');

    Route::post('education-types', [EducationTypeController::class, 'store'])
        ->name('education-types.store')
        ->middleware('can:create_educationtype');

    Route::get('education-types/{educationType}', [EducationTypeController::class, 'show'])
        ->name('education-types.show')
        ->middleware('can:view_educationtype');

    Route::get('education-types/{educationType}/edit', [EducationTypeController::class, 'edit'])
        ->name('education-types.edit')
        ->middleware('can:edit_educationtype');

    Route::put('education-types/{educationType}', [EducationTypeController::class, 'update'])
        ->name('education-types.update')
        ->middleware('can:edit_educationtype');

    Route::delete('education-types/{educationType}', [EducationTypeController::class, 'destroy'])
        ->name('education-types.destroy')
        ->middleware('can:delete_educationtype');
});

// Routes for Semister
Route::middleware(['auth'])->group(function() {
    Route::get('semisters', [SemisterController::class, 'index'])
        ->name('semisters.index')
        ->middleware('can:view_semister');

    Route::get('semisters/create', [SemisterController::class, 'create'])
        ->name('semisters.create')
        ->middleware('can:create_semister');

    Route::post('semisters', [SemisterController::class, 'store'])
        ->name('semisters.store')
        ->middleware('can:create_semister');

    Route::get('semisters/{semister}', [SemisterController::class, 'show'])
        ->name('semisters.show')
        ->middleware('can:view_semister');

    Route::get('semisters/{semister}/edit', [SemisterController::class, 'edit'])
        ->name('semisters.edit')
        ->middleware('can:edit_semister');

    Route::put('semisters/{semister}', [SemisterController::class, 'update'])
        ->name('semisters.update')
        ->middleware('can:edit_semister');

    Route::delete('semisters/{semister}', [SemisterController::class, 'destroy'])
        ->name('semisters.destroy')
        ->middleware('can:delete_semister');
});



// Routes for Course
Route::middleware(['auth'])->group(function() {
    Route::get('courses', [CourseController::class, 'index'])
        ->name('courses.index')
        ->middleware('can:view_course');

    Route::get('courses/create/{teacher_id?}', [CourseController::class, 'create'])
        ->name('courses.create')
        ->middleware('can:create_course');

    Route::post('courses', [CourseController::class, 'store'])
        ->name('courses.store')
        ->middleware('can:create_course');

    Route::get('courses/{course}', [CourseController::class, 'show'])
        ->name('courses.show')
        ->middleware('can:view_course');

    Route::get('courses/{course}/edit', [CourseController::class, 'edit'])
        ->name('courses.edit')
        ->middleware('can:edit_course');

    Route::put('courses/{course}', [CourseController::class, 'update'])
        ->name('courses.update')
        ->middleware('can:edit_course');

    Route::delete('courses/{course}', [CourseController::class, 'destroy'])
        ->name('courses.destroy')
        ->middleware('can:delete_course');
    Route::post('courses/{course}/toggle-featured', [CourseController::class, 'toggleFeatured'])
        ->name('courses.toggle-featured')
        ->middleware('can:edit_course');
    Route::post('courses/{course}/students/{student}/watches', [CourseController::class, 'updateWatches'])
        ->name('courses.students.watches.update');
});



// Routes for Chapter
Route::middleware(['auth'])->group(function() {
    Route::get('chapters', [ChapterController::class, 'index'])
        ->name('chapters.index')
        ->middleware('can:view_chapter');

    Route::get('chapters/create/{course_id?}', [ChapterController::class, 'create'])
        ->name('chapters.create')
        ->middleware('can:create_chapter');

    Route::post('chapters', [ChapterController::class, 'store'])
        ->name('chapters.store')
        ->middleware('can:create_chapter');

    Route::get('chapters/{chapter}', [ChapterController::class, 'show'])
        ->name('chapters.show')
        ->middleware('can:view_chapter');

    Route::get('chapters/{chapter}/edit', [ChapterController::class, 'edit'])
        ->name('chapters.edit')
        ->middleware('can:edit_chapter');

    Route::put('chapters/{chapter}', [ChapterController::class, 'update'])
        ->name('chapters.update')
        ->middleware('can:edit_chapter');

    Route::delete('chapters/{chapter}', [ChapterController::class, 'destroy'])
        ->name('chapters.destroy')
        ->middleware('can:delete_chapter');
    Route::post('chapters/{chapter}/students/{student}/watches', [ChapterController::class, 'updateWatches'])
        ->name('chapters.students.watches.update');
});

// Routes for Lesson
Route::middleware(['auth'])->group(function() {
    Route::get('lessons', [LessonController::class, 'index'])
        ->name('lessons.index')
        ->middleware('can:view_lesson');

    Route::get('lessons/create/{chapter_id?}', [LessonController::class, 'create'])
        ->name('lessons.create')
        ->middleware('can:create_lesson');

    Route::post('lessons', [LessonController::class, 'store'])
        ->name('lessons.store')
        ->middleware('can:create_lesson');

    Route::get('lessons/{lesson}', [LessonController::class, 'show'])
        ->name('lessons.show')
        ->middleware('can:view_lesson');

    Route::get('lessons/{lesson}/edit', [LessonController::class, 'edit'])
        ->name('lessons.edit')
        ->middleware('can:edit_lesson');

    Route::put('lessons/{lesson}', [LessonController::class, 'update'])
        ->name('lessons.update')
        ->middleware('can:edit_lesson');

    Route::delete('lessons/{lesson}', [LessonController::class, 'destroy'])
        ->name('lessons.destroy')
        ->middleware('can:delete_lesson');

    Route::post('lessons/{lesson}/toggle-featured', [LessonController::class, 'toggleFeatured'])
        ->name('lessons.toggle-featured')
        ->middleware('can:edit_lesson');

    Route::post('lessons/{lesson}/attachments', [LessonAttachmentController::class, 'store'])->name('lessons.attachments.store')->middleware('can:create_lesson');
    Route::delete('lesson-attachments/{attachment}', [LessonAttachmentController::class, 'destroy'])->name('lessons.attachments.destroy')->middleware('can:delete_lesson');
    Route::post('lesson-attachments/{attachment}/toggle-featured', [LessonAttachmentController::class, 'toggleFeatured'])->name('lesson-attachments.toggle-featured')->middleware('can:edit_lesson');
    Route::post('lessons/{lesson}/students/{student}/watches', [LessonController::class, 'updateWatches'])
        ->name('lessons.students.watches.update');
});


// Routes for Exam
Route::middleware(['auth'])->group(function() {
    Route::get('exams', [ExamController::class, 'index'])
        ->name('exams.index')
        ->middleware('can:view_exam');

    Route::get('exams/create', [ExamController::class, 'create'])
        ->name('exams.create')
        ->middleware('can:create_exam');

    Route::post('exams', [ExamController::class, 'store'])
        ->name('exams.store')
        ->middleware('can:create_exam');

    Route::get('exams/{exam}', [ExamController::class, 'show'])
        ->name('exams.show')
        ->middleware('can:view_exam');

    Route::get('exams/{exam}/edit', [ExamController::class, 'edit'])
        ->name('exams.edit')
        ->middleware('can:edit_exam');

    Route::put('exams/{exam}', [ExamController::class, 'update'])
        ->name('exams.update')
        ->middleware('can:edit_exam');

    Route::delete('exams/{exam}', [ExamController::class, 'destroy'])
        ->name('exams.destroy')
        ->middleware('can:delete_exam');

    Route::get('exams/{exam}/submissions', [ExamController::class, 'submissions'])
        ->name('exams.submissions')
        ->middleware('can:view_exam');
});

Route::middleware(['auth'])->group(function () {
    // Student exam taking routes
    Route::get('exams/{exam}/take', [ExamTakingController::class, 'takeExam'])->name('exams.take');
    Route::post('exams/{exam}/submit', [ExamTakingController::class, 'submitExam'])->name('exams.submit');
    Route::post('exams/{exam}/save-answer', [ExamTakingController::class, 'saveAnswer'])->name('exams.save-answer');
    Route::get('exam-attempts/{attempt}/results', [ExamTakingController::class, 'showResults'])->name('exam-attempts.results');

    // Admin exam management routes (if not already added)
    Route::get('exams/{exam}/questions/{question}', [ExamController::class, 'getQuestion'])->name('exams.get-question');
    Route::post('exams/{exam}/questions', [ExamController::class, 'addQuestion'])->name('exams.add-question');
    Route::delete('exams/{exam}/questions/{question}', [ExamController::class, 'removeQuestion'])->name('exams.remove-question');
    Route::put('exams/{exam}/questions/{question}', [ExamController::class, 'updateQuestion'])->name('exams.update-question');
    Route::patch('exams/{exam}/toggle-active', [ExamController::class, 'toggleActive'])->name('exams.toggle-active');

    // Exam attempts management (for instructors)
    Route::get('exams/{exam}/attempts', [ExamController::class, 'viewAttempts'])->name('exams.attempts');
    Route::get('exam-attempts/{attempt}/grade', [ExamController::class, 'gradeAttempt'])->name('exam-attempts.grade');
    Route::post('exam-attempts/{attempt}/grade', [ExamController::class, 'updateGrade'])->name('exam-attempts.update-grade');
});


// Routes for Homework

Route::middleware(['auth'])->group(function () {
    // Homework routes
    Route::get('homework', [HomeworkController::class, 'index'])
        ->name('homework.index')
        ->middleware('can:view_homework');

    Route::get('homework/create', [HomeworkController::class, 'create'])
        ->name('homework.create')
        ->middleware('can:create_homework');

    Route::post('homework', [HomeworkController::class, 'store'])
        ->name('homework.store')
        ->middleware('can:create_homework');

    Route::get('homework/{homework}', [HomeworkController::class, 'show'])
        ->name('homework.show')
        ->middleware('can:view_homework');

    Route::get('homework/{homework}/edit', [HomeworkController::class, 'edit'])
        ->name('homework.edit')
        ->middleware('can:edit_homework');

    Route::put('homework/{homework}', [HomeworkController::class, 'update'])
        ->name('homework.update')
        ->middleware('can:edit_homework');

    Route::delete('homework/{homework}', [HomeworkController::class, 'destroy'])
        ->name('homework.destroy')
        ->middleware('can:delete_homework');

    Route::get('homework/{homework}/submissions', [HomeworkController::class, 'submissions'])
        ->name('homework.submissions')
        ->middleware('can:view_homework');
    Route::patch('homework/{homework}/toggle-status', [HomeworkController::class, 'toggleStatus'])->name('homework.toggle-status');

    // Homework Question routes (inline)
    Route::post('homework/{homework}/questions', [HomeworkQuestionController::class, 'store'])->name('homework-questions.store');
    Route::patch('homework-questions/{question}', [HomeworkQuestionController::class, 'update'])->name('homework-questions.update');
    Route::delete('homework-questions/{question}', [HomeworkQuestionController::class, 'destroy'])->name('homework-questions.destroy');
});

// Routes for Center Exam Questions
Route::middleware(['auth'])->group(function () {
    Route::post('center-exams/{centerExam}/questions', [CenterExamQuestionController::class, 'store'])->name('center-exam-questions.store');
    Route::patch('center-exam-questions/{question}', [CenterExamQuestionController::class, 'update'])->name('center-exam-questions.update');
    Route::delete('center-exam-questions/{question}', [CenterExamQuestionController::class, 'destroy'])->name('center-exam-questions.destroy');
    Route::get('center-exam-questions/{question}/edit', [CenterExamQuestionController::class, 'edit'])->name('center-exam-questions.edit');
});


Route::middleware(['auth'])->group(function () {
    Route::get('settings/bulk-edit', [SettingsController::class, 'bulkEdit'])
        ->name('settings.bulkEdit')
    ->middleware('can:update_settings');
    Route::put('settings/bulk-update', [SettingsController::class, 'bulkUpdate'])
        ->name('settings.bulkUpdate')
        ->middleware('can:update_settings');
});

// Routes for Gamification
Route::middleware(['auth'])->group(function() {
        Route::get('action-points', [\App\Http\Controllers\Dashboard\GamificationController::class, 'editActionPoints'])->name('action-points.edit')->middleware('can:edit_action_points');
    Route::put('action-points', [\App\Http\Controllers\Dashboard\GamificationController::class, 'updateActionPoints'])->name('action-points.update')->middleware('can:edit_action_points');
        Route::get('reward-points', [\App\Http\Controllers\Dashboard\GamificationController::class, 'editRewardPoints'])->name('reward-points.edit')->middleware('can:edit_reward_points');
    Route::put('reward-points', [\App\Http\Controllers\Dashboard\GamificationController::class, 'updateRewardPoints'])->name('reward-points.update')->middleware('can:edit_reward_points');

    Route::get('luck-wheel', [\App\Http\Controllers\Dashboard\GamificationController::class, 'editLuckWheelItems'])->name('luck-wheel.edit')->middleware('can:edit_luck_wheel');
    Route::put('luck-wheel', [\App\Http\Controllers\Dashboard\GamificationController::class, 'updateLuckWheelItems'])->name('luck-wheel.update')->middleware('can:edit_luck_wheel');
});



// Routes for Banner
Route::middleware(['auth'])->group(function() {
    Route::get('banners', [BannerController::class, 'index'])
        ->name('banners.index')
        ->middleware('can:view_banner');

    Route::get('banners/create', [BannerController::class, 'create'])
        ->name('banners.create')
        ->middleware('can:create_banner');

    Route::post('banners', [BannerController::class, 'store'])
        ->name('banners.store')
        ->middleware('can:create_banner');

    Route::get('banners/{banner}', [BannerController::class, 'show'])
        ->name('banners.show')
        ->middleware('can:view_banner');

    Route::get('banners/{banner}/edit', [BannerController::class, 'edit'])
        ->name('banners.edit')
        ->middleware('can:edit_banner');

    Route::put('banners/{banner}', [BannerController::class, 'update'])
        ->name('banners.update')
        ->middleware('can:edit_banner');

    Route::delete('banners/{banner}', [BannerController::class, 'destroy'])
        ->name('banners.destroy')
        ->middleware('can:delete_banner');
});


// Routes for Code
Route::middleware(['auth'])->group(function() {
    Route::get('codes', [CodeController::class, 'index'])
        ->name('codes.index')
        ->middleware('can:view_code');

    Route::get('codes/create', [CodeController::class, 'create'])
        ->name('codes.create')
        ->middleware('can:create_code');

    Route::post('codes', [CodeController::class, 'store'])
        ->name('codes.store')
        ->middleware('can:create_code');

    Route::get('codes/export', [CodeController::class, 'export'])
        ->name('codes.export')
        ->middleware('can:view_code');

    Route::get('codes/{code}', [CodeController::class, 'show'])
        ->name('codes.show')
        ->middleware('can:view_code');

    Route::get('codes/{code}/edit', [CodeController::class, 'edit'])
        ->name('codes.edit')
        ->middleware('can:edit_code');

    Route::put('codes/{code}', [CodeController::class, 'update'])
        ->name('codes.update')
        ->middleware('can:edit_code');

    Route::delete('codes/{code}', [CodeController::class, 'destroy'])
        ->name('codes.destroy')
        ->middleware('can:delete_code');
});
