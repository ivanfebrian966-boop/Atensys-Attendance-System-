<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\HolidayDate;
use App\Models\Permission;
use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        Attendance::syncMissingCheckouts();

        $pendingPermissions = Permission::with('employee.division')
            ->where('permission_status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $divisions = Division::orderBy('division_name')->get();

        return view('Admin_HR.pages.attendance', compact('pendingPermissions', 'divisions'));
    }

    /**
     * Process QR Code scan for Check In / Check Out
     * QR Format: ATTENSYS:EMP:NIP
     */
    public function processQr(Request $request)
    {
        try {
            // ── Blokir scan saat hari libur nasional ──────────────────────
            $today = Carbon::today();
            $holiday = HolidayDate::whereDate('date', $today)->first();
            if ($holiday) {
                return response()->json([
                    'success' => false,
                    'message' => '🎉 Hari ini adalah hari libur: ' . $holiday->name . '. Sistem absensi ditutup.'
                ], 403);
            }
            // ─────────────────────────────────────────────────────────────

            $qrData = $request->input('qr_data');
            
            if (!$qrData) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code data tidak ditemukan'
                ], 400);
            }
            
            // Parse QR code
            $nip = $qrData;
            $qrTimestamp = null;
            if (strpos($qrData, ':') !== false) {
                $parts = explode(':', $qrData);
                // Format dari Frontend: ATTENSYS:EMP:NIP:TIMESTAMP
                if (count($parts) >= 3 && $parts[0] === 'ATTENSYS' && $parts[1] === 'EMP') {
                    $nip = $parts[2];
                    if (isset($parts[3])) {
                        $qrTimestamp = (int)$parts[3];
                    }
                } else {
                    $nip = end($parts); // Fallback
                }
            }

            // Validasi keaslian & kedaluwarsa QR Code jika bukan dalam environment testing
            if (!app()->environment('testing')) {
                if ($qrTimestamp) {
                    // Konversi timestamp milidetik ke detik jika perlu (JS Date.now() menghasilkan milidetik)
                    if ($qrTimestamp > 9999999999) {
                        $qrTimestamp = (int)($qrTimestamp / 1000);
                    }
                    
                    $diff = abs(time() - $qrTimestamp);
                    
                    // Beri batas waktu toleransi 30 detik (mengakomodasi refresh rate 10s + selisih waktu/koneksi)
                    if ($diff > 30) {
                        return response()->json([
                            'success' => false,
                            'message' => 'QR Code kedaluwarsa. Silakan gunakan QR Code terbaru di layar handphone.'
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Format QR Code tidak valid atau kedaluwarsa.'
                    ], 400);
                }
            }
            
            $employee = Employee::where('nip', $nip)->first();
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan tidak ditemukan. NIP: ' . $nip
                ], 404);
            }
            
            $now = Carbon::now();
            
            // 1. Cek Atd ada atau enggak, kalau enggak ada, maka anggap dia belum menekan cekin
            $todayAttendance = Attendance::where('nip', $nip)
                ->whereDate('created_at', $today)
                ->first();
            
            if (!$todayAttendance) {
                // Belum ada attendance hari ini -> Check In
                $attendance = Attendance::create([
                    'nip' => $nip,
                    'check_in' => $now,
                    'attendance_status' => $this->determineStatus($now),
                    'qr_code' => $qrData,
                ]);
                
                return response()->json([
                    'success' => true,
                    'type' => 'check_in',
                    'message' => 'Check In berhasil',
                    'employee' => $employee->name,
                    'time' => $now->format('H:i:s'),
                    'status' => $attendance->attendance_status
                ]);

            } elseif (!is_null($todayAttendance->check_out)) {
                // 4. Kalau sudah ada check_out maka dia sudah tidak bisa check in atau check out lagi.
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan ' . $employee->name . ' sudah check out hari ini'
                ], 400);

            } elseif (!is_null($todayAttendance->check_in) && !is_null($todayAttendance->attendance_status) && is_null($todayAttendance->check_out)) {
                // 2. Kalau ada atd, tapi ada isi di kolom check_in, maka anggap dia perlu verifikasi (jeda waktu sebelum bisa check_out)
                $checkInTime = Carbon::parse($todayAttendance->check_in);
                if ($now->diffInMinutes($checkInTime) < 5) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jeda waktu terlalu singkat untuk verifikasi. Anda baru saja Check In.'
                    ], 400);
                }

                // 3. Kalau ada atd, ada isi kolom check_in dan ada isi kolom attendance_status 
                // dan tidak ada isi kolom check_out, maka dianggap akan check_out
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
                // Kondisi lainnya (misal status Sick/Permission yang tidak memiliki check_in)
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat melakukan absensi QR saat ini. (Status saat ini: ' . $todayAttendance->attendance_status . ')'
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
        Attendance::syncMissingCheckouts();
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
                        'status' => $attendance->attendance_status,
                        'check_in' => $attendance->check_in ? Carbon::parse($attendance->check_in)->format('H:i:s') : '—',
                        'check_out' => $attendance->check_out ? Carbon::parse($attendance->check_out)->format('H:i:s') : '—',
                        'duration' => $this->calculateDuration($attendance->check_in, $attendance->check_out),
                        'information' => $this->getPermissionInfo($attendance->nip, $attendance->attendance_status, $parsedDate)
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
                'attendance_status' => 'nullable|string',
                'check_in' => 'nullable',
                'check_out' => 'nullable',
            ]);

            $date = Carbon::parse($validated['date']);
            
            $check_in = $validated['check_in'] ? $date->copy()->setTimeFromTimeString($validated['check_in']) : null;
            $check_out = $validated['check_out'] ? $date->copy()->setTimeFromTimeString($validated['check_out']) : null;

            $status = $validated['attendance_status'] ?? null;
            if (!$status) {
                if ($check_in) {
                    $limit = $date->copy()->setTime(8, 0, 0);
                    $status = $check_in->greaterThan($limit) ? 'Late' : 'Present';
                } elseif ($check_out) {
                    $status = 'Present';
                } else {
                    $status = 'Absent';
                }
            }

            Attendance::create([
                'nip' => $validated['nip'],
                'attendance_status' => $status,
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
                'attendance_status' => 'nullable|string',
                'check_in' => 'nullable',
                'check_out' => 'nullable',
                'information' => 'nullable|string',
            ]);

            $date = Carbon::parse($validated['date']);
            
            $check_in = $validated['check_in'] ? $date->copy()->setTimeFromTimeString($validated['check_in']) : null;
            $check_out = $validated['check_out'] ? $date->copy()->setTimeFromTimeString($validated['check_out']) : null;

            $status = $validated['attendance_status'] ?? null;
            if (!$status) {
                if ($check_in) {
                    $limit = $date->copy()->setTime(8, 0, 0);
                    $status = $check_in->greaterThan($limit) ? 'Late' : 'Present';
                } elseif ($check_out) {
                    $status = 'Present';
                } else {
                    $originalStatus = $attendance->attendance_status;
                    if (in_array($originalStatus, ['Sick', 'Permission'])) {
                        $status = $originalStatus;
                    } else {
                        $status = 'Absent';
                    }
                }
            }

            $attendance->update([
                'attendance_status' => $status,
                'check_in' => $check_in,
                'check_out' => $check_out,
                'created_at' => $date->setTime(7,0,0),
                'updated_at' => Carbon::now(),
            ]);

            // Jika status adalah Sick atau Permission, update keterangan di tabel permissions
            if (in_array($status, ['Sick', 'Permission'])) {
                Permission::updateOrCreate(
                    [
                        'nip' => $attendance->nip,
                        'start_date' => $date->toDateString(),
                    ],
                    [
                        'completion_date' => $date->toDateString(),
                        'type' => $status === 'Sick' ? 'Sick' : 'Leave',
                        'permission_status' => 'Approved',
                        'information' => $validated['information'] ?? null,
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
        Attendance::syncMissingCheckouts();
        try {
            $date = $request->input('date', Carbon::today()->toDateString());
            $parsedDate = Carbon::parse($date)->toDateString();
            
            $stats = Attendance::whereDate('created_at', $parsedDate)
                ->selectRaw('COUNT(*) as total, 
                            SUM(CASE WHEN attendance_status = "Present" THEN 1 ELSE 0 END) as present,
                            SUM(CASE WHEN attendance_status = "Absent" THEN 1 ELSE 0 END) as absent,
                            SUM(CASE WHEN attendance_status = "Late" THEN 1 ELSE 0 END) as late,
                            SUM(CASE WHEN attendance_status = "Holiday" THEN 1 ELSE 0 END) as holiday')
                ->first();

            $sickCount = Permission::where('type', 'Sick')
                ->where('permission_status', 'Approved')
                ->whereDate('start_date', '<=', $parsedDate)
                ->whereDate('completion_date', '>=', $parsedDate)
                ->count();

            $permCount = Permission::where('type', 'Leave')
                ->where('permission_status', 'Approved')
                ->whereDate('start_date', '<=', $parsedDate)
                ->whereDate('completion_date', '>=', $parsedDate)
                ->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $stats->total ?? 0,
                    'present' => $stats->present ?? 0,
                    'absent' => $stats->absent ?? 0,
                    'late' => $stats->late ?? 0,
                    'sick' => $sickCount,
                    'permission' => $permCount,
                    'holiday' => $stats->holiday ?? 0
                ]
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
        if ($status !== 'Permission') return '—';

        $perm = Permission::where('nip', $nip)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('completion_date', '>=', $date)
            ->where('permission_status', 'Approved')
            ->first();

        return $perm ? $perm->information : '—';
    }

    public function getEmployees()
    {
        $employees = Employee::where('role', 'Employee')
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
            $permission->update(['permission_status' => 'Approved']);

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
                        'attendance_status' => 'Permission',
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
            $permission->update(['permission_status' => 'Rejected']);
            return back()->with('success', 'Perizinan ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
