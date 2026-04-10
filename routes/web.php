<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\DashboardHRController;
use App\Http\Controllers\ProfileHRController;
use Illuminate\Support\Facades\Route;


Route::get('/home', [HomeController::class, 'home']);
Route::get('/super_admin', [SuperAdminController::class, 'index']);
Route::get('/dashboardHR', [DashboardHRController::class, 'index'])->name('dashboardHR');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/profileHR', [ProfileHRController::class, 'index'])->name('profileHR');
