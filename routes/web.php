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
use Illuminate\Support\Facades\Route;

// hakaman awal
Route::get('/home', [HomeController::class, 'home']);

// Super Admin
Route::get('/super_admin', [SuperAdminController::class, 'index'])->name('super_admin');

// login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Admin HR
Route::get('/dashboardHR', [DashboardHRController::class, 'index'])->name('dashboardHR');
Route::get('/profileHR', [ProfileHRController::class, 'index'])->name('profileHR');
Route::get('/HRmanage', [HRmanageController::class, 'index'])->name('HRmanage');
Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');
Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
Route::get('/sidebar', [SidebarController::class, 'index'])->name('sidebar');

