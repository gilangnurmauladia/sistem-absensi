<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Employee;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Sistem Absensi Kafe Sunset Bridge
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard berdasarkan role
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('employee.dashboard');
});

// =========================================================
// ADMIN ROUTES
// =========================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Karyawan (CRUD)
    Route::middleware(['permission:manage-employees'])->group(function () {
        Route::resource('employees', Admin\EmployeeController::class);
    });

    // Absensi
    Route::middleware(['permission:manage-attendance'])->group(function () {
        Route::get('/attendances/recap', [Admin\AttendanceController::class, 'recap'])->name('attendances.recap');
        Route::resource('attendances', Admin\AttendanceController::class)->except(['show']);
        Route::get('/attendances/{employee}', [Admin\AttendanceController::class, 'show'])->name('attendances.show');
    });

    // Jadwal Shift
    Route::middleware(['permission:manage-schedules'])->group(function () {
        Route::get('/schedules', [Admin\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/create', [Admin\ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [Admin\ScheduleController::class, 'store'])->name('schedules.store');
        Route::delete('/schedules/{schedule}', [Admin\ScheduleController::class, 'destroy'])->name('schedules.destroy');
    });

    // Penilaian Karyawan
    Route::middleware(['permission:manage-performance'])->group(function () {
        Route::resource('performances', Admin\PerformanceReviewController::class);
    });

    // Manajemen Izin
    Route::middleware(['permission:manage-leaves'])->group(function () {
        Route::get('/leaves', [Admin\LeaveController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/{leave}', [Admin\LeaveController::class, 'show'])->name('leaves.show');
        Route::post('/leaves/{leave}/approve', [Admin\LeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('/leaves/{leave}/reject', [Admin\LeaveController::class, 'reject'])->name('leaves.reject');
    });
 
    // User Management (Super Admin Only)
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::resource('users', Admin\UserController::class);
        Route::resource('roles', Admin\RoleController::class);
        Route::resource('permissions', Admin\PermissionController::class);
    });
});

// =========================================================
// EMPLOYEE ROUTES
// =========================================================
Route::prefix('employee')->name('employee.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [Employee\DashboardController::class, 'index'])->name('dashboard');

    // Absensi
    Route::post('/attendance/check-in', [Employee\AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [Employee\AttendanceController::class, 'checkOut'])->name('attendance.check-out');

    // Izin
    Route::get('/leaves', [Employee\LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [Employee\LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [Employee\LeaveController::class, 'store'])->name('leaves.store');

    // Jadwal
    Route::get('/schedules', [Employee\ScheduleController::class, 'index'])->name('schedules.index');
});

require __DIR__.'/auth.php';
