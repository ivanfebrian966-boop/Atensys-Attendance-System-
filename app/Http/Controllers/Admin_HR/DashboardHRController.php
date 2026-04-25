<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardHRController extends Controller
{
    public function index()
    {
        $pendingPermissionsCount = Permission::where('status', 'Pending')->count();
        $features = [
            "QR Code Attendance" => "Quick attendance using QR scanning",
            "Attendance History" => "Complete attendance records",
            "Employee Management" => "Manage employee data easily",
            "Reports" => "Automatic attendance reports"
        ];

        $user = Auth::user();

        // Retrieve total employees (role: karyawan)
        $totalEmployees = Employee::where('role', 'karyawan')->count();

        $today = Carbon::today();
        
        $present = Attendance::whereDate('created_at', $today)->where('status', 'Present')->count();
        $late = Attendance::whereDate('created_at', $today)->where('status', 'Late')->count();
        $absent = Attendance::whereDate('created_at', $today)->where('status', 'Absent')->count();
        
        $sick = Attendance::whereDate('created_at', $today)->where('status', 'Sick')->count();
        $permission = Attendance::whereDate('created_at', $today)->where('status', 'Permission')->count();

        $recorded = $present + $late + $sick + $permission + $absent;

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
            $count = Attendance::whereDate('created_at', $date)
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

        $recentAttendances = Attendance::with('employee')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
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
            'recentAttendances' => $recentAttendances,
            'pendingPermissionsCount' => $pendingPermissionsCount
        ]);
    }
}
