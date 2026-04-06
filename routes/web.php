<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Super_Admin\SuperAdminController;
use App\Http\Controllers\Admin_HR\DashboardHRController;
use App\Http\Controllers\Admin_HR\ProfileHRController;
use App\Http\Controllers\Admin_HR\HRmanageController;
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
