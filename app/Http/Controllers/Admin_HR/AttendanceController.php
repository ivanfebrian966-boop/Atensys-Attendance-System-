<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class AttendanceController extends Controller
{
    public function index()
    {
        $pendingPermissions = Permission::with('employee.division')
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Admin_HR.pages.attendance', compact('pendingPermissions'));
    }

    /**
     * Process QR Code scan for Check In / Check Out
     * QR Format: ATTENSYS:EMP:NIP
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
            
            // Parse QR code
            $nip = $qrData;
            if (strpos($qrData, ':') !== false) {
                $parts = explode(':', $qrData);
                $nip = end($parts);
            }
            
            $employee = Employee::where('nip', $nip)->first();
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan tidak ditemukan. NIP: ' . $nip
                ], 404);
            }
            
            $today = Carbon::today();
            $now = Carbon::now();
            
            // Check if checked in today
            $todayAttendance = Attendance::where('nip', $nip)
                ->whereDate('check_in', $today)
                ->first();
            
            if (!$todayAttendance) {
                // Check In
                $attendance = Attendance::create([
                    'nip' => $nip,
                    'check_in' => $now,
                    'status' => $this->determineStatus($now),
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
                // Check Out
                $todayAttendance->update([
                    'check_out' => $now
                ]);

                return response()->json([
                    'success' => true,
                    'type' => 'check_out',
                    'message' => 'Check Out berhasil',
                    'employee' => $employee->name,
                    'time' => $now->format('H:i:s')
                ]);
            } else {
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
    
    private function determineStatus($checkInTime)
    {
        $limit = Carbon::today()->setHour(8)->setMinute(0);
        return $checkInTime->greaterThan($limit) ? 'Late' : 'Present';
    }
    
    public function getAttendanceData(Request $request)
    {
        try {
            $date = $request->input('date', Carbon::today()->toDateString());
            $parsedDate = Carbon::parse($date)->toDateString();
            
            $attendances = Attendance::whereDate('created_at', $parsedDate)
                ->with('employee.division')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($attendance) use ($parsedDate) {
                    return [
                        'id' => $attendance->attendance_id,
                        'name' => $attendance->employee?->name ?? 'Unknown',
                        'division' => $attendance->employee?->division->division_name ?? '—',
                        'date' => Carbon::parse($attendance->created_at)->format('Y-m-d'),
                        'status' => $attendance->status,
                        'check_in' => $attendance->check_in ? Carbon::parse($attendance->check_in)->format('H:i:s') : '—',
                        'check_out' => $attendance->check_out ? Carbon::parse($attendance->check_out)->format('H:i:s') : '—',
                        'duration' => $this->calculateDuration($attendance->check_in, $attendance->check_out),
                        'information' => $this->getPermissionInfo($attendance->nip, $attendance->status, $parsedDate)
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

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nip' => 'required|exists:employees,nip',
                'date' => 'required|date',
                'status' => 'required|string',
                'check_in' => 'nullable',
                'check_out' => 'nullable',
            ]);

            $date = Carbon::parse($validated['date']);
            
            $check_in = $validated['check_in'] ? $date->copy()->setTimeFromTimeString($validated['check_in']) : null;
            $check_out = $validated['check_out'] ? $date->copy()->setTimeFromTimeString($validated['check_out']) : null;

            Attendance::create([
                'nip' => $validated['nip'],
                'status' => $validated['status'],
                'check_in' => $check_in,
                'check_out' => $check_out,
                'qr_code' => 'MANUAL',
                'created_at' => $date->setTime(7,0,0),
                'updated_at' => $date,
            ]);

            return response()->json(['success' => true, 'message' => 'Data absensi berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            
            $validated = $request->validate([
                'date' => 'required|date',
                'status' => 'required|string',
                'check_in' => 'nullable',
                'check_out' => 'nullable',
                'information' => 'nullable|string',
            ]);

            $date = Carbon::parse($validated['date']);
            
            $check_in = $validated['check_in'] ? $date->copy()->setTimeFromTimeString($validated['check_in']) : null;
            $check_out = $validated['check_out'] ? $date->copy()->setTimeFromTimeString($validated['check_out']) : null;

            $attendance->update([
                'status' => $validated['status'],
                'check_in' => $check_in,
                'check_out' => $check_out,
                'created_at' => $date->setTime(7,0,0),
                'updated_at' => Carbon::now(),
            ]);

            // Jika status adalah Sick atau Permission, update keterangan di tabel permissions
            if (in_array($validated['status'], ['Sick', 'Permission'])) {
                Permission::updateOrCreate(
                    [
                        'nip' => $attendance->nip,
                        'start_date' => $date->toDateString(),
                    ],
                    [
                        'completion_date' => $date->toDateString(),
                        'type' => $validated['status'],
                        'status' => 'Accepted',
                        'information' => $validated['information'],
                    ]
                );
            }

            return response()->json(['success' => true, 'message' => 'Data absensi berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $attendance->delete();
            return response()->json(['success' => true, 'message' => 'Data absensi berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getStats(Request $request)
    {
        try {
            $date = $request->input('date', Carbon::today()->toDateString());
            $parsedDate = Carbon::parse($date)->toDateString();
            
            $stats = Attendance::whereDate('created_at', $parsedDate)
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
    
    private function calculateDuration($checkIn, $checkOut)
    {
        if (!$checkIn || !$checkOut) return '—';
        $duration = Carbon::parse($checkOut)->diff(Carbon::parse($checkIn));
        return sprintf('%02d:%02d', $duration->h, $duration->i);
    }

    private function getPermissionInfo($nip, $status, $date)
    {
        if (!in_array($status, ['Sick', 'Permission'])) return '—';

        $perm = Permission::where('nip', $nip)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('completion_date', '>=', $date)
            ->where('status', 'Accepted')
            ->first();

        return $perm ? $perm->information : '—';
    }

    public function getEmployees()
    {
        $employees = Employee::where('role', 'karyawan')
            ->orderBy('name')
            ->get(['nip', 'name', 'position']);

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    public function approvePermission($id)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::findOrFail($id);
            $permission->update(['status' => 'Accepted']);

            $start = Carbon::parse($permission->start_date);
            $end = Carbon::parse($permission->completion_date);

            for ($date = $start; $date->lte($end); $date->addDay()) {
                $exists = Attendance::where('nip', $permission->nip)
                    ->whereDate('created_at', $date->toDateString())
                    ->exists();

                if (!$exists) {
                    $attendanceDate = $date->copy()->setTime(7, 0, 0);
                    Attendance::create([
                        'nip' => $permission->nip,
                        'check_in' => $attendanceDate,
                        'status' => $permission->type,
                        'qr_code' => 'SYSTEM',
                        'created_at' => $attendanceDate,
                        'updated_at' => $attendanceDate,
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Perizinan disetujui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function rejectPermission($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->update(['status' => 'Rejected']);
            return back()->with('success', 'Perizinan ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
