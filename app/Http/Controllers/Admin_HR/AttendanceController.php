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
     * Process QR Code scan for Check In / Check Out
     * QR Format: ATTENSYS:EMP:USER_ID or employee_id
     */
    public function processQr(Request $request)
    {
        try {
            $qrData = $request->input('qr_data');
            
            if (!$qrData) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code data tidak ditemukan'
                ], 400);
            }
            
            // Parse QR code - bisa format ATTENSYS:EMP:ID atau langsung ID
            $employeeId = $qrData;
            
            if (strpos($qrData, ':') !== false) {
                $parts = explode(':', $qrData);
                $employeeId = $parts[count($parts) - 1]; // Get last part as employee ID
            }
            
            // Find the employee
            $employee = User::find($employeeId);
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan tidak ditemukan. Barcode: ' . $employeeId
                ], 404);
            }
            
            $today = Carbon::today();
            $now = Carbon::now();
            
            // Check if employee has checked in today
            $todayAttendance = Attendance::where('user_id', $employeeId)
                ->whereDate('date', $today)
                ->first();
            
            if (!$todayAttendance) {
                // First scan = Check In
                $attendance = Attendance::create([
                    'user_id' => $employeeId,
                    'date' => $today,
                    'check_in' => $now,
                    'status' => $this->determineStatus($now),
                    'notes' => 'Auto check-in via QR'
                ]);
                
                return response()->json([
                    'success' => true,
                    'type' => 'check_in',
                    'message' => 'Check In berhasil',
                    'employee' => $employee->name,
                    'time' => $now->format('H:i:s'),
                    'status' => $attendance->status
                ]);
            } elseif (!$todayAttendance->check_out) {
                // Already checked in, now check out
                $checkInTime = Carbon::parse($todayAttendance->check_in);
                $duration = $now->diff($checkInTime);
                
                $todayAttendance->update([
                    'check_out' => $now,
                    'notes' => ($todayAttendance->notes ?? '') . ' | Auto check-out via QR'
                ]);
                
                return response()->json([
                    'success' => true,
                    'type' => 'check_out',
                    'message' => 'Check Out berhasil',
                    'employee' => $employee->name,
                    'time' => $now->format('H:i:s'),
                    'duration' => $duration->format('%H:%I')
                ]);
            } else {
                // Already checked in and out
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan ' . $employee->name . ' sudah check out hari ini'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Determine attendance status based on check-in time
     */
    private function determineStatus($checkInTime)
    {
        $hour = $checkInTime->hour;
        $minute = $checkInTime->minute;
        $checkInMinutes = $hour * 60 + $minute;
        
        // Assume office start time is 08:00 (480 minutes)
        $startTime = 8 * 60;
        
        if ($checkInMinutes <= $startTime) {
            return 'Present';
        } else {
            return 'Late';
        }
    }
    
    /**
     * Get attendance data for table
     */
    public function getAttendanceData(Request $request)
    {
        try {
            $date = $request->input('date', Carbon::today()->toDateString());
            $parsedDate = Carbon::parse($date)->toDateString();
            
            $attendances = Attendance::where('date', $parsedDate)
                ->with('user')
                ->orderBy('check_in', 'desc')
                ->get()
                ->map(function ($attendance) {
                    return [
                        'id' => $attendance->id,
                        'name' => $attendance->user?->name ?? 'Unknown',
                        'division' => $attendance->user?->division ?? 'Unknown',
                        'date' => $attendance->date->format('Y-m-d'),
                        'status' => $attendance->status,
                        'check_in' => $attendance->check_in ? $attendance->check_in->format('H:i:s') : '—',
                        'check_out' => $attendance->check_out ? $attendance->check_out->format('H:i:s') : '—',
                        'duration' => $this->calculateDuration($attendance->check_in, $attendance->check_out),
                        'notes' => $attendance->notes ?? ''
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $attendances
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get stats for dashboard
     */
    public function getStats(Request $request)
    {
        try {
            $date = $request->input('date', Carbon::today()->toDateString());
            $parsedDate = Carbon::parse($date)->toDateString();
            
            $stats = Attendance::where('date', $parsedDate)
                ->selectRaw('COUNT(*) as total, 
                            SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present,
                            SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent,
                            SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as late,
                            SUM(CASE WHEN status = "Sick" THEN 1 ELSE 0 END) as sick,
                            SUM(CASE WHEN status = "Permission" THEN 1 ELSE 0 END) as permission')
                ->first();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Calculate duration between check-in and check-out
     */
    private function calculateDuration($checkIn, $checkOut)
    {
        if (!$checkIn || !$checkOut) {
            return '—';
        }
        
        $duration = Carbon::parse($checkOut)->diff(Carbon::parse($checkIn));
        return sprintf('%02d:%02d', $duration->h, $duration->i);
    }
}


