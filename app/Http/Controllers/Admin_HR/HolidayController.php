<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\HolidayDate;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    /**
     * Tampilkan halaman kalender hari libur.
     */
    public function index()
    {
        $holidays = HolidayDate::orderBy('date', 'asc')->get();

        // Format untuk kalender JS: { "Y-m-d": ["Nama1","Nama2"] }
        $holidayMap = [];
        foreach ($holidays as $h) {
            $holidayMap[$h->date->format('Y-m-d')] = $h->names ?? [];
        }

        return view('Admin_HR.pages.holidays', compact('holidays', 'holidayMap'));
    }

    /**
     * Simpan hari libur baru.
     * Mendukung lebih dari 1 nama hari libur dalam satu tanggal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date'    => 'required|date|unique:holiday_dates,date',
            'names'   => 'required|array|min:1',
            'names.*' => 'required|string|max:150',
        ], [
            'date.unique'    => 'Tanggal ini sudah terdaftar sebagai hari libur.',
            'date.required'  => 'Tanggal hari libur wajib diisi.',
            'names.required' => 'Nama hari libur wajib diisi.',
            'names.*.required' => 'Nama hari libur tidak boleh kosong.',
        ]);

        try {
            DB::beginTransaction();

            $names = array_values(array_filter(array_map('trim', $request->names)));

            $holiday = HolidayDate::create([
                'date'  => $request->date,
                'names' => $names,
            ]);

            // Buat absensi Holiday untuk semua karyawan aktif
            $this->generateHolidayAttendances($holiday->date);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hari libur "' . implode(' & ', $names) . '" berhasil disimpan.',
                'holiday' => [
                    'id'        => $holiday->id,
                    'date'      => $holiday->date->format('Y-m-d'),
                    'names'     => $holiday->names,
                    'label'     => $holiday->names_label,
                    'formatted' => $holiday->date->translatedFormat('l, d F Y'),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update nama-nama hari libur pada tanggal tertentu.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'names'   => 'required|array|min:1',
            'names.*' => 'required|string|max:150',
        ], [
            'names.required'   => 'Minimal 1 nama hari libur harus diisi.',
            'names.*.required' => 'Nama hari libur tidak boleh kosong.',
        ]);

        try {
            $holiday = HolidayDate::findOrFail($id);
            $names   = array_values(array_filter(array_map('trim', $request->names)));

            $holiday->update(['names' => $names]);

            return response()->json([
                'success' => true,
                'message' => 'Hari libur berhasil diperbarui.',
                'holiday' => [
                    'id'        => $holiday->id,
                    'date'      => $holiday->date->format('Y-m-d'),
                    'names'     => $holiday->names,
                    'label'     => $holiday->names_label,
                    'formatted' => $holiday->date->translatedFormat('l, d F Y'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus hari libur beserta absensi Holiday yang di-generate otomatis.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $holiday = HolidayDate::findOrFail($id);
            $dateStr = $holiday->date->format('Y-m-d');

            // Hapus hanya absensi auto-generated (qr_code = SYSTEM-HOLIDAY)
            Attendance::whereDate('created_at', $dateStr)
                ->where('qr_code', 'SYSTEM-HOLIDAY')
                ->delete();

            $holiday->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hari libur berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint JSON: cek apakah tanggal tertentu adalah hari libur.
     */
    public function check(Request $request)
    {
        $date    = $request->input('date', Carbon::today()->toDateString());
        $holiday = HolidayDate::whereDate('date', $date)->first();

        return response()->json([
            'is_holiday' => (bool) $holiday,
            'names'      => $holiday ? ($holiday->names ?? []) : [],
            'name'       => $holiday ? $holiday->names_label : null,
            'date'       => $date,
        ]);
    }

    /**
     * Generate absensi 'Holiday' untuk semua karyawan aktif
     * yang belum memiliki absensi atau izin disetujui pada tanggal tersebut.
     * Melewati akhir pekan (Sabtu & Minggu).
     */
    private function generateHolidayAttendances(Carbon $date): void
    {
        if ($date->isWeekend()) {
            return;
        }

        $dateStr   = $date->toDateString();
        $employees = Employee::where('role', 'Employee')->where('status', 'Aktif')->get();

        foreach ($employees as $emp) {
            $existsAttendance = Attendance::where('nip', $emp->nip)
                ->whereDate('created_at', $dateStr)
                ->exists();

            if ($existsAttendance) continue;

            $existsPermission = Permission::where('nip', $emp->nip)
                ->where('permission_status', 'Approved')
                ->whereDate('start_date', '<=', $dateStr)
                ->whereDate('completion_date', '>=', $dateStr)
                ->exists();

            if ($existsPermission) continue;

            $attendanceDate = $date->copy()->setTime(7, 0, 0);
            Attendance::create([
                'nip'               => $emp->nip,
                'check_in'          => null,
                'check_out'         => null,
                'attendance_status' => 'Holiday',
                'qr_code'           => 'SYSTEM-HOLIDAY',
                'created_at'        => $attendanceDate,
                'updated_at'        => $attendanceDate,
            ]);
        }
    }
}
