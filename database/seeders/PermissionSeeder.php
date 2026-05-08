<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Permission;
use App\Models\Attendance;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil hanya 5 karyawan secara acak untuk diberi data perizinan
        $employees = Employee::where('role', 'karyawan')->inRandomOrder()->take(5)->get();

        if ($employees->isEmpty()) {
            $this->command->info('Tidak ada employee dengan role "karyawan". Silakan buat terlebih dahulu.');
            return;
        }

        foreach ($employees as $employee) {
            // Hapus data lama agar tidak duplikat saat seeding ulang
            Permission::where('nip', $employee->nip)->delete();

            $types = ['Permission', 'Sick'];
            $statuses = ['Approved', 'Pending', 'Rejected'];

            $reasons = [
                'Sick' => ['Demam dan flu tinggi', 'Sakit kepala migrain', 'Istirahat pasca kontrol dokter', 'Pemeriksaan laboratorium', 'Gejala tipes'],
                'Permission' => ['Urusan keluarga mendesak', 'Acara pernikahan keluarga inti', 'Mengurus dokumen kependudukan', 'Keperluan perbankan', 'Takziah kerabat'],
            ];

            // Buat 5 data permission untuk 5 hari terakhir (termasuk hari ini)
            for ($i = 0; $i < 5; $i++) {
                $type = $types[array_rand($types)];
                
                // Hari ini (index 0) dibuat Pending agar bisa di-test ACC oleh user
                // Hari lainnya random
                $status = ($i === 0) ? 'Pending' : $statuses[array_rand($statuses)];
                
                $reason = $reasons[$type][array_rand($reasons[$type])];
                $date = Carbon::today()->subDays($i)->setTime(7, 0, 0);

                Permission::create([
                    'nip' => $employee->nip,
                    'type' => $type,
                    'status' => $status,
                    'information' => $reason,
                    'file' => null,
                    'start_date' => $date,
                    'completion_date' => $date,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // HANYA jika status Approved, buat record di Attendance
                // (Ini mensimulasikan data yang sudah disetujui admin di masa lalu)
                if ($status === 'Approved') {
                    Attendance::create([
                        'nip' => $employee->nip,
                        'status' => $type,
                        'check_in' => $date,
                        'qr_code' => 'SYSTEM',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }
            }
        }

        $this->command->info("Berhasil membuat data dummy permission untuk " . $employees->count() . " karyawan!");
    }
}
