<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardHRController extends Controller
{
    public function index()
    {
        $features = [
            "QR Code Attendance" => "Quick attendance using QR scanning",
            "Attendance History" => "Complete attendance records",
            "Employee Management" => "Manage employee data easily",
            "Reports" => "Automatic attendance reports"
        ];

        $user = Auth::user();
        return view('/Admin_HR/dashboardHR', compact('features'));
    }
}
