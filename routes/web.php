<?php

use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RoleController;
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


Route::get('/dashboard2', function () {

    return view('dashboard.temp.index');
})->name('dashboard');



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
    Route::get('/statistics', function () {

        return view('dashboard.temp.index');
    })->name('dashboard');
    // Profile edit page
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Update profile
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Delete profile image
    Route::delete('/profile/delete-image', [ProfileController::class, 'deleteImage'])->name('profile.delete-image');

    // Logout route
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');
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

    Route::get('teachers/{teacher}', [TeacherController::class, 'show'])
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
});