<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
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

        // Retrieve total employees
        $totalEmployees = User::where('role', 'employee')->count();
        if ($totalEmployees === 0) {
            // fallback if no employees found or role differs
            $totalEmployees = User::count(); 
        }

        $today = Carbon::today();
        
        $present = Attendance::whereDate('date', $today)->where('status', 'Present')->count();
        $absentRecord = Attendance::whereDate('date', $today)->where('status', 'Absent')->count();
        $late = Attendance::whereDate('date', $today)->where('status', 'Late')->count();
        $sick = Attendance::whereDate('date', $today)->where('status', 'Sick')->count();
        $permission = Attendance::whereDate('date', $today)->where('status', 'Permission')->count();

        $recorded = $present + $late + $sick + $permission + $absentRecord;
        $absent = $absentRecord;
        if ($recorded < $totalEmployees) {
            $absent += ($totalEmployees - $recorded);
        }

        $presentPct = $totalEmployees > 0 ? round(($present / $totalEmployees) * 100) : 0;
        $absentPct = $totalEmployees > 0 ? round(($absent / $totalEmployees) * 100) : 0;
        $latePct = $totalEmployees > 0 ? round(($late / $totalEmployees) * 100) : 0;
        $sickPct = $totalEmployees > 0 ? round(($sick / $totalEmployees) * 100) : 0;
        $permissionPct = $totalEmployees > 0 ? round(($permission / $totalEmployees) * 100) : 0;
        
        // Chart data
        $startOfWeek = Carbon::now()->startOfWeek();
        $chartData = [];
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $totalAttendanceThisWeek = 0;
        $totalDays = 0;
        
        for ($i = 0; $i < 6; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $count = Attendance::whereDate('date', $date)
                ->whereIn('status', ['Present', 'Late'])
                ->count();
            
            $pct = $totalEmployees > 0 ? round(($count / $totalEmployees) * 100) : 0;
            $chartData[$days[$i]] = $pct;
            
            if ($date <= Carbon::today()) {
                $totalAttendanceThisWeek += $pct;
                $totalDays++;
            }
        }
        
        $avgAttendance = $totalDays > 0 ? round($totalAttendanceThisWeek / $totalDays, 1) : 0;

        $recentAttendances = Attendance::with('user')
            ->whereDate('date', Carbon::today())
            ->orderBy('check_in', 'desc')
            ->take(5)
            ->get();

        return view('Admin_HR.pages.dashboard', [
            'features' => $features,
            'totalEmployees' => $totalEmployees,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'sick' => $sick,
            'permission' => $permission,
            'presentPct' => $presentPct,
            'absentPct' => $absentPct,
            'latePct' => $latePct,
            'sickPct' => $sickPct,
            'permissionPct' => $permissionPct,
            'chartData' => $chartData,
            'avgAttendance' => $avgAttendance,
            'recentAttendances' => $recentAttendances
        ]);
    }
}
