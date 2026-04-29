<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Super_Admin\SuperAdminController;
use App\Http\Controllers\Admin_HR\DashboardHRController;
use App\Http\Controllers\Admin_HR\ProfileHRController;
use App\Http\Controllers\Admin_HR\EmployeesController;
use App\Http\Controllers\Admin_HR\ReportsController;
use App\Http\Controllers\Admin_HR\AttendanceController;
use App\Http\Controllers\Admin_HR\LeaveController;
use App\Http\Controllers\Admin_HR\SidebarController;
use App\Http\Controllers\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;

// hakaman awal
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/home', [HomeController::class, 'home']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Super Admin
Route::group(['prefix' => 'super-admin', 'as' => 'super_admin.', 'middleware' => ['role:super_admin']], function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::get('/employees', [SuperAdminController::class, 'employees'])->name('employees');
    Route::get('/admins', [SuperAdminController::class, 'admins'])->name('admins');
    Route::get('/divisions', [SuperAdminController::class, 'divisions'])->name('divisions');
    Route::get('/profile', [SuperAdminController::class, 'profile'])->name('profile');
    
    Route::post('/profile', [SuperAdminController::class, 'updateProfile'])->name('update_profile');
    
    Route::post('/employee', [SuperAdminController::class, 'storeEmployee'])->name('store_employee');
    Route::post('/employee/{id}', [SuperAdminController::class, 'updateEmployee'])->name('update_employee');
    Route::delete('/employee/{id}', [SuperAdminController::class, 'deleteEmployee'])->name('delete_employee');

    Route::post('/hr-admin', [SuperAdminController::class, 'storeHrAdmin'])->name('store_hr_admin');
    Route::post('/hr-admin/{id}', [SuperAdminController::class, 'updateHrAdmin'])->name('update_hr_admin');
    Route::delete('/hr-admin/{id}', [SuperAdminController::class, 'deleteHrAdmin'])->name('delete_hr_admin');

    Route::post('/division', [SuperAdminController::class, 'storeDivision'])->name('store_division');
    Route::post('/division/{id}', [SuperAdminController::class, 'updateDivision'])->name('update_division');
    Route::delete('/division/{id}', [SuperAdminController::class, 'deleteDivision'])->name('delete_division');
});

// Admin HR
Route::group(['prefix' => 'admin-hr', 'as' => 'admin-hr.', 'middleware' => ['role:admin_hr']], function () {
    Route::get('/dashboard', [DashboardHRController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileHRController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileHRController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [ProfileHRController::class, 'changePassword'])->name('profile.password');

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/data', [ReportsController::class, 'getData'])->name('reports.data');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('/attendance/update/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/delete/{id}', [AttendanceController::class, 'destroy'])->name('attendance.delete');
    Route::post('/attendance/process-qr', [AttendanceController::class, 'processQr'])->name('attendance.process-qr');
    Route::get('/attendance/data', [AttendanceController::class, 'getAttendanceData'])->name('attendance.data');
    Route::get('/attendance/stats', [AttendanceController::class, 'getStats'])->name('attendance.stats');
    Route::get('/attendance/employees', [AttendanceController::class, 'getEmployees'])->name('attendance.employees');

    Route::post('/attendance/permission/{id}/approve', [AttendanceController::class, 'approvePermission'])->name('attendance.permission.approve');
    Route::post('/attendance/permission/{id}/reject', [AttendanceController::class, 'rejectPermission'])->name('attendance.permission.reject');

    Route::get('/sidebar', [SidebarController::class, 'index'])->name('sidebar');
    Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');

    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves');
    Route::get('/leaves/data', [LeaveController::class, 'getData'])->name('leaves.data');
    Route::post('/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::put('/leaves/{id}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{id}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
    Route::post('/leaves/mark-absent', [LeaveController::class, 'markAbsent'])->name('leaves.mark-absent');
});

// Employee Routes
Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard')->middleware('role:karyawan');

Route::prefix('employee')->middleware('role:karyawan')->group(function () {
    Route::get('/attendance', [EmployeeController::class, 'attendance'])->name('employee.attendance');
    Route::get('/history', [EmployeeController::class, 'history'])->name('employee.history');
    Route::get('/leave', [EmployeeController::class, 'leave'])->name('employee.leave');
    Route::get('/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
    Route::post('/attendance/checkin', [EmployeeController::class, 'checkIn'])->name('employee.attendance.checkin');
    Route::post('/attendance/checkout', [EmployeeController::class, 'checkOut'])->name('employee.attendance.checkout');
    Route::post('/attendance/permission', [EmployeeController::class, 'storePermission'])->name('employee.attendance.permission');
});

// Legacy employee route
Route::get('/employee', [EmployeeController::class, 'dashboard'])->name('employee')->middleware('role:karyawan');