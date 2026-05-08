<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Show attendance page
     */
    public function index()
    {
        return view('Admin_HR.attendance');
    }

    /**
     * Process QR Code scan for check in/check out
     */
    public function processQr(Request $request)
    {
        try {
            $qrData = $request->input('qr_data');
            
            // Parse QR data: format ATTENSYS:EMP:EMP001:EMPNAME
            if (!preg_match('/ATTENSYS:EMP:([A-Z0-9]+):(.+)/', $qrData, $matches)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format QR code tidak valid'
                ], 400);
            }
            
            $empId = $matches[1];
            $empName = $matches[2];
            
            // Cek apakah karyawan exists
            $employee = Employee::where('nip', $empId)->first();
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan tidak ditemukan'
                ], 404);
            }
            
            $today = Carbon::now()->format('Y-m-d');
            $currentTime = Carbon::now()->format('H:i:s');
            
            // Cek apakah sudah ada check in hari ini
            $attendance = Attendance::where('nip', $empId)
                ->whereDate('check_in', $today)
                ->first();
            
            if (!$attendance) {
                // Belum check in, buat record baru dengan check in
                $attendance = Attendance::create([
                    'nip' => $empId,
                    'check_in' => Carbon::now(),
                    'attendance_status' => $this->determineStatus($currentTime),
                    'qr_code' => $qrData,
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Check In berhasil',
                    'type' => 'check_in',
                    'time' => $currentTime,
                    'employee' => $employee->name,
                    'division' => $employee->division ?? 'Engineering'
                ]);
            } else {
                // Sudah check in, update check out
                if ($attendance->check_out) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah check out hari ini. Hubungi admin jika ingin koreksi.'
                    ], 400);
                }
                
                $attendance->update([
                    'check_out' => Carbon::now()
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Check Out berhasil',
                    'type' => 'check_out',
                    'time' => $currentTime,
                    'employee' => $employee->name,
                    'duration' => $this->calculateDuration($attendance->check_in, $currentTime)
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Determine attendance status based on check in time
     */
    private function determineStatus($checkInTime)
    {
        $time = strtotime($checkInTime);
        $officeHour = strtotime('08:00:00');
        
        if ($time <= $officeHour) {
            return 'Present';
        } else {
            return 'Late';
        }
    }
    
    /**
     * Calculate duration between check in and check out
     */
    private function calculateDuration($checkIn, $checkOut)
    {
        $in = strtotime($checkIn);
        $out = strtotime($checkOut);
        $duration = $out - $in;
        
        $hours = floor($duration / 3600);
        $minutes = ($duration % 3600) / 60;
        
        return sprintf('%02d:%02d', $hours, $minutes);
    }
    
    /**
     * Get attendance data for display (today or filtered)
     */
    public function getAttendanceData(Request $request)
    {
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $division = $request->input('division', null);
        
        $query = Attendance::with('employee.division')
            ->whereDate('check_in', $date);
        
        if ($division) {
            $query->whereHas('employee', function($q) use ($division) {
                $q->where('division_id', $division);
            });
        }
        
        $data = $query->orderBy('check_in', 'asc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
