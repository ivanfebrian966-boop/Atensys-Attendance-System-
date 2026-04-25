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
            // Siapkan array dengan komposisi acak (3 Present, 3 Late, 3 Absent, 3 Sick, 3 Permission)
            $statuses = [
                'Present', 'Present', 'Present',
                'Late', 'Late', 'Late',
                'Absent', 'Absent', 'Absent',
                'Sick', 'Sick', 'Sick',
                'Permission', 'Permission', 'Permission'
            ];
            shuffle($statuses);

            // Mulai dari 15 hari yang lalu
            $startDate = Carbon::today()->subDays(14); 

            // Hapus absensi dummy yang mungkin sudah ada agar tidak menumpuk
            Attendance::where('nip', $employee->nip)->delete();

            foreach ($statuses as $index => $status) {
                $date = $startDate->copy()->addDays($index);

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
                // Untuk Absent, Sick, Permission, checkIn dan checkOut tetap null
                // Dan qrCode tetap 'SYSTEM' (sebagai penanda dibuat otomatis)

                Attendance::create([
                    'nip' => $employee->nip,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'status' => $status,
                    'qr_code' => $qrCode,
                    'created_at' => $date->copy()->setTime(7, 0, 0),
                    'updated_at' => $date->copy()->setTime(7, 0, 0),
                ]);
            }
        }

        $this->command->info("Berhasil membuat data dummy attendance untuk " . $employees->count() . " karyawan!");
    }
}
