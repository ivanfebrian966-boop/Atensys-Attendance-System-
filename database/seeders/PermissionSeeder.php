<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Permission;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('role', '!=', 'Super Admin')->get();
        $startDate = Carbon::today()->subDays(10);

        foreach ($employees as $emp) {
            for ($i = 0; $i <= 10; $i++) {
                $date = $startDate->copy()->addDays($i);
                if ($date->dayOfWeek === Carbon::SUNDAY) continue;

                $rand = rand(1, 100);
                
                if ($rand <= 5) {
                    // Sick (5% chance)
                    $sickCats = ['Accident', 'Mild Illness (Flu / Fever)', 'Medical Checkup'];
                    Permission::create([
                        'nip' => $emp->nip,
                        'type' => 'Sick',
                        'sick_category' => $sickCats[array_rand($sickCats)],
                        'permission_status' => 'Approved',
                        'information' => 'Sakit mendadak',
                        'start_date' => $date->toDateString(),
                        'completion_date' => $date->toDateString(),
                        'created_at' => $date->copy()->setTime(7, 0, 0),
                    ]);

                    Attendance::create([
                        'nip' => $emp->nip,
                        'attendance_status' => 'Permission',
                        'check_in' => null,
                        'qr_code' => 'SYSTEM',
                        'created_at' => $date->copy()->setTime(7, 0, 0),
                    ]);
                } elseif ($rand <= 10) {
                    // Leave (5% chance)
                    $leaveCats = ['Annual Leave', 'Personal Leave', 'Family Event'];
                    Permission::create([
                        'nip' => $emp->nip,
                        'type' => 'Leave',
                        'leave_category' => $leaveCats[array_rand($leaveCats)],
                        'permission_status' => 'Approved',
                        'information' => 'Ada urusan keluarga',
                        'start_date' => $date->toDateString(),
                        'completion_date' => $date->toDateString(),
                        'created_at' => $date->copy()->setTime(7, 0, 0),
                    ]);

                    Attendance::create([
                        'nip' => $emp->nip,
                        'attendance_status' => 'Permission',
                        'check_in' => null,
                        'qr_code' => 'SYSTEM',
                        'created_at' => $date->copy()->setTime(7, 0, 0),
                    ]);
                }
            }
        }
    }
}
