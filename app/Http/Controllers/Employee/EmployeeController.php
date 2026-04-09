<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $todayAttendance = Attendance::where('user_id', $user->id)->whereDate('date', Carbon::today())->first();
        
        // Monthly stats
        $monthStats = [
            'present' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->where('status', 'Present')->count(),
            'late' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->where('status', 'Late')->count(),
            'absent' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->where('status', 'Absent')->count(),
            'sick_permission' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->whereIn('status', ['Sick', 'Permission'])->count(),
        ];
        
        $recentAttendances = Attendance::where('user_id', $user->id)->orderBy('date', 'desc')->limit(7)->get();
        
        // Generate unique QR code data for employee (using user ID)
        // Format: ATTENSYS:EMP:USER_ID:TIMESTAMP for scanning
        $qrCodeData = 'ATTENSYS:EMP:' . $user->id . ':' . now()->timestamp;
        
        return view('employee.dashboard', compact('todayAttendance', 'monthStats', 'recentAttendances', 'user', 'qrCodeData'));
    }

    public function checkIn(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'check_in' => Carbon::now(),
            'status' => 'Present'
        ]);
        
        return back()->with('success', 'Checked in successfully!');
    }

    public function checkOut(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $attendance = Attendance::where('user_id', $user->id)->whereDate('date', Carbon::today())->first();
        if ($attendance) {
            $attendance->update(['check_out' => Carbon::now()]);
        }
        
        return back()->with('success', 'Checked out successfully!');
    }
}