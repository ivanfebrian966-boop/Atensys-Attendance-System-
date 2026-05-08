<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('role', '!=', 'Super Admin')->get();
        $startDate = Carbon::today()->subDays(10);

        foreach ($employees as $emp) {
            for ($i = 0; $i <= 10; $i++) {
                $date = $startDate->copy()->addDays($i);
                if ($date->dayOfWeek === Carbon::SUNDAY) continue;

                // Check if already seeded by PermissionSeeder
                if (Attendance::where('nip', $emp->nip)->whereDate('created_at', $date)->exists()) continue;

                $rand = rand(1, 100);
                
                if ($rand <= 95) {
                    // Regular Attendance (85% chance if we account for the 5% Sick/5% Leave taken by other seeder)
                    // Actually since we skip if exists, we just handle the rest here.
                    $isLate = rand(1, 10) > 8; // 20% chance of being late
                    $checkInHour = $isLate ? 8 : 7;
                    $checkInMin = $isLate ? rand(1, 30) : rand(30, 59);
                    
                    Attendance::create([
                        'nip' => $emp->nip,
                        'attendance_status' => $isLate ? 'Late' : 'Present',
                        'check_in' => $date->copy()->setTime($checkInHour, $checkInMin, rand(0, 59)),
                        'check_out' => $date->copy()->setTime(17, rand(0, 30), rand(0, 59)),
                        'qr_code' => 'QR_DUMMY_' . $emp->nip,
                        'created_at' => $date->copy()->setTime(7, 0, 0),
                    ]);
                } else {
                    // Absent (5% chance)
                    Attendance::create([
                        'nip' => $emp->nip,
                        'attendance_status' => 'Absent',
                        'check_in' => null,
                        'check_out' => null,
                        'qr_code' => 'SYSTEM',
                        'created_at' => $date->copy()->setTime(7, 0, 0),
                    ]);
                }
            }
        }
    }
}
