<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        return view('dashboardHR', compact('features'));
    }
}