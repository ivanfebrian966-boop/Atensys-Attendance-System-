<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class DummyAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari semua karyawan
        $employees = Employee::where('role', 'karyawan')->get();

        if ($employees->isEmpty()) {
            $this->command->info('Tidak ada employee dengan role "karyawan". Silakan buat terlebih dahulu.');
            return;
        }

        foreach ($employees as $employee) {
            // Siapkan array dengan komposisi acak (5 Present, 5 Late, 5 Absent)
            $statuses = [
                'Present', 'Present', 'Present', 'Present', 'Present',
                'Late', 'Late', 'Late', 'Late', 'Late',
                'Absent', 'Absent', 'Absent', 'Absent', 'Absent'
            ];
            shuffle($statuses);

            // Mulai dari 20 hari yang lalu sampai hari ini (21 hari total)
            $startDate = Carbon::today()->subDays(20); 

            // Hapus absensi dummy yang mungkin sudah ada agar tidak menumpuk
            Attendance::where('nip', $employee->nip)->delete();

            for ($i = 0; $i <= 20; $i++) {
                $date = $startDate->copy()->addDays($i)->setTime(7, 0, 0);
                
                // Cek apakah ada perizinan di tabel permissions untuk tanggal ini
                $perm = \App\Models\Permission::where('nip', $employee->nip)
                    ->whereDate('start_date', '<=', $date)
                    ->whereDate('completion_date', '>=', $date)
                    ->first();

                if ($perm) {
                    // Jika izin disetujui, buat record di attendance
                    if ($perm->status === 'Accepted') {
                        Attendance::create([
                            'nip' => $employee->nip,
                            'check_in' => $date,
                            'status' => $perm->type,
                            'qr_code' => 'SYSTEM',
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]);
                    }
                    // Jika Pending/Rejected, jangan buat absensi (sesuai aturan 1 aksi)
                    continue;
                }

                // Jika tidak ada izin, buat data absensi acak (Present/Late/Absent)
                $status = $statuses[array_rand($statuses)];
                $checkIn = null;
                $checkOut = null;
                $qrCode = 'SYSTEM';

                if ($status === 'Present') {
                    $checkIn = $date->copy()->setTime(7, rand(30, 59), rand(0, 59));
                    $checkOut = $date->copy()->setTime(17, rand(0, 30), rand(0, 59));
                    $qrCode = 'MANUAL';
                } elseif ($status === 'Late') {
                    $checkIn = $date->copy()->setTime(rand(8, 9), rand(15, 30), rand(0, 59));
                    $checkOut = $date->copy()->setTime(17, rand(0, 30), rand(0, 59));
                    $qrCode = 'MANUAL';
                } 

                Attendance::create([
                    'nip' => $employee->nip,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'status' => $status,
                    'qr_code' => $qrCode,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        $this->command->info("Berhasil membuat data dummy attendance untuk " . $employees->count() . " karyawan!");
    }
}
