<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Super_Admin\SuperAdminController;
use App\Http\Controllers\Admin_HR\DashboardHRController;
use App\Http\Controllers\Admin_HR\ProfileHRController;
use App\Http\Controllers\Admin_HR\HRmanageController;
use App\Http\Controllers\Admin_HR\EmployeesController;
use App\Http\Controllers\Admin_HR\ReportsController;
use App\Http\Controllers\Admin_HR\AttendanceController;
use App\Http\Controllers\Admin_HR\SidebarController;
use App\Http\Controllers\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;

// hakaman awal
Route::get('/home', [HomeController::class, 'home']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Super Admin
Route::get('/super-admin', [SuperAdminController::class, 'index'])->name('super_admin');

// Admin HR
Route::get('/dashboardHR', [DashboardHRController::class, 'index'])->name('dashboardHR');
Route::get('/profileHR', [ProfileHRController::class, 'index'])->name('profileHR');
Route::get('/HRmanage', [HRmanageController::class, 'index'])->name('HRmanage');
Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');
Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
Route::post('/attendance/process-qr', [AttendanceController::class, 'processQr'])->name('attendance.process-qr');
Route::get('/attendance/data', [AttendanceController::class, 'getAttendanceData'])->name('attendance.data');
Route::get('/attendance/stats', [AttendanceController::class, 'getStats'])->name('attendance.stats');
Route::get('/sidebar', [SidebarController::class, 'index'])->name('sidebar');

// Employee Routes
Route::prefix('employee')->middleware('auth')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::get('/attendance', [EmployeeController::class, 'attendance'])->name('employee.attendance');
    Route::get('/history', [EmployeeController::class, 'history'])->name('employee.history');
    Route::get('/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
    Route::post('/attendance/checkin', [EmployeeController::class, 'checkIn'])->name('employee.attendance.checkin');
    Route::post('/attendance/checkout', [EmployeeController::class, 'checkOut'])->name('employee.attendance.checkout');
});

// Legacy employee route
Route::get('/employee', [EmployeeController::class, 'dashboard'])->middleware('auth')->name('employee');