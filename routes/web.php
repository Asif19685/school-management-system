<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdmissionsController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\VisitorsController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\FeesController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ✅ Guest Routes (No Login Required)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// ✅ Auth Routes (Login Required) - SAB KUCH ISMEIN RAKHEIN
Route::middleware('auth')->group(function () {

    // Redirect root
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============ ADMISSIONS MODULE ============
  Route::prefix('student-admissions')->name('admissions.')->group(function () {
    Route::get('/', [AdmissionsController::class, 'index'])->name('index');
    Route::get('/data', [AdmissionsController::class, 'getStudentsData'])->name('data');
    Route::get('/create', [AdmissionsController::class, 'create'])->name('create');
    Route::post('/', [AdmissionsController::class, 'store'])->name('store');  // Changed from /store to /
    Route::get('/{id}', [AdmissionsController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [AdmissionsController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AdmissionsController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdmissionsController::class, 'destroy'])->name('destroy');
    Route::get('/counts', [AdmissionsController::class, 'getCounts'])->name('counts');  // Fixed duplicate name
});

    // ============ STUDENTS MODULE ============
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentsController::class, 'index'])->name('index');
        Route::get('/data', [StudentsController::class, 'getStudentsData'])->name('data');
        Route::post('/attendance/mark', [StudentsController::class, 'markAttendance'])->name('attendance.mark');
    });

    // ============ TEACHERS MODULE ============
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeachersController::class, 'index'])->name('index');
        Route::get('/data', [TeachersController::class, 'getTeachersData'])->name('data');
        Route::get('/create', [TeachersController::class, 'create'])->name('create');
        Route::post('/', [TeachersController::class, 'store'])->name('store');
        Route::get('/{id}', [TeachersController::class, 'show'])->name('show');
        Route::put('/{id}', [TeachersController::class, 'update'])->name('update');
        Route::delete('/{id}', [TeachersController::class, 'destroy'])->name('destroy');
    });

    // ============ COURSES MODULE ============
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [CoursesController::class, 'index'])->name('index');
        Route::get('/data', [CoursesController::class, 'getCoursesData'])->name('data');
    });

    // ============ FEES MODULE ============
    Route::prefix('fees')->name('fees.')->group(function () {
        Route::get('/', [FeesController::class, 'index'])->name('index');
        Route::get('/data', [FeesController::class, 'getFeesData'])->name('data');
        Route::get('/{studentId}/student-fees', [FeesController::class, 'getStudentFees'])->name('student.fees');
        Route::post('/{studentId}/submit', [FeesController::class, 'submitFee'])->name('submit');
        Route::get('/{id}/student-detail', [FeesController::class, 'showStudentDetail'])->name('student.detail');
    });

    // ============ VISITORS MODULE ============
    Route::prefix('visitors')->name('visitors.')->group(function () {
        Route::get('/', [VisitorsController::class, 'index'])->name('index');
        Route::get('/data', [VisitorsController::class, 'getVisitorsData'])->name('data');
        Route::get('/create', [VisitorsController::class, 'create'])->name('create');
        Route::post('/', [VisitorsController::class, 'store'])->name('store');
        Route::get('/{id}', [VisitorsController::class, 'show'])->name('show');
        Route::put('/{id}', [VisitorsController::class, 'update'])->name('update');
        Route::delete('/{id}', [VisitorsController::class, 'destroy'])->name('destroy');
    });

    // ============ ATTENDANCE MODULE ============
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/data', [AttendanceController::class, 'getAttendanceData'])->name('data');
        Route::get('/summary', [AttendanceController::class, 'getDailySummary'])->name('summary');
        Route::post('/mark', [AttendanceController::class, 'markAttendance'])->name('mark');
    });

    // ============ EXAMS MODULE ============
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', [ExamsController::class, 'index'])->name('index');
        Route::get('/data', [ExamsController::class, 'getExamsData'])->name('data');
    });

    // ============ NOTIFICATIONS MODULE ============
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationsController::class, 'index'])->name('index');
        Route::get('/data', [NotificationsController::class, 'getNotificationsData'])->name('data');
    });

    // ============ REPORTS MODULE ============
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::get('/data', [ReportsController::class, 'getReportsData'])->name('data');
    });

    // ============ LIBRARY MODULE ============
    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/', [LibraryController::class, 'index'])->name('index');
        Route::get('/data', [LibraryController::class, 'getLibraryData'])->name('data');
    });

    // ============ ROLES MODULE ============
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RolesController::class, 'index'])->name('index');
        Route::get('/data', [RolesController::class, 'getRolesData'])->name('data');
    });

    // ============ PROFILE ============
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============ EMAIL VERIFICATION ============
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // ============ PASSWORD CONFIRM ============
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // ============ LOGOUT ============
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
