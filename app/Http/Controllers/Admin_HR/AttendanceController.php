<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('/Admin_HR/attendance');
    }

    /**
     * Process QR Code scan from employee
     * QR Format: ATTENSYS:EMP:USER_ID:TIMESTAMP
     */
    public function processQRCode(Request $request)
    {
        $qrData = $request->input('qr_data');
        
        // Parse QR code data
        $parts = explode(':', $qrData);
        
        if (count($parts) < 3 || $parts[0] !== 'ATTENSYS' || $parts[1] !== 'EMP') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR Code Format'
            ], 400);
        }
        
        $employeeId = $parts[2] ?? null;
        
        if (!$employeeId) {
            return response()->json([
                'success' => false,
                'message' => 'Employee ID not found in QR Code'
            ], 400);
        }
        
        // Find the employee
        $employee = User::find($employeeId);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        // Check if already marked present today
        $todayAttendance = Attendance::where('user_id', $employeeId)
            ->whereDate('date', Carbon::today())
            ->first();
        
        if ($todayAttendance && $todayAttendance->check_in) {
            return response()->json([
                'success' => false,
                'message' => $employee->name . ' already checked in today',
                'employee' => $employee->name
            ], 400);
        }
        
        // Record attendance
        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $employeeId,
                'date' => Carbon::today()
            ],
            [
                'check_in' => Carbon::now(),
                'status' => 'Present'
            ]
        );
        
        return response()->json([
            'success' => true,
            'message' => $employee->name . ' successfully marked as present',
            'employee' => $employee->name,
            'timestamp' => $attendance->check_in->format('H:i:s')
        ]);
    }
}

