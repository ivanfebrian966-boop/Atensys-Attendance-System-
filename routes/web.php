<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;


Route::get('/home', [HomeController::class, 'home']);
Route::get('/super-admin/dashboard', [SuperAdminController::class, 'index'])->name('super_admin');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');