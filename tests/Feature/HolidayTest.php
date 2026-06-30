<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\Employee;
use App\Models\HolidayDate;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HolidayTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $employee;
    private $division;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup base data
        $this->division = Division::create([
            'division_name' => 'IT Department'
        ]);

        $this->admin = Employee::create([
            'nip' => 'ADM0001',
            'name' => 'HR Admin User',
            'role' => 'Admin HR',
            'password' => bcrypt('password'),
            'position' => 'HR Manager',
            'email' => 'admin@attensys.com',
            'division_id' => $this->division->division_id,
            'no_hp' => '08123456789',
            'alamat' => 'HR Office Address',
            'status' => 'Aktif'
        ]);

        $this->employee = Employee::create([
            'nip' => 'EMP0001',
            'name' => 'Employee John',
            'role' => 'Employee',
            'password' => bcrypt('password'),
            'position' => 'Developer',
            'email' => 'john@attensys.com',
            'division_id' => $this->division->division_id,
            'no_hp' => '08123456780',
            'alamat' => 'Employee Address',
            'status' => 'Aktif'
        ]);
    }

    public function test_admin_can_add_holiday_and_generates_attendances()
    {
        $this->actingAs($this->admin);

        // Next Monday is guaranteed to be a weekday
        $date = Carbon::now()->next(Carbon::MONDAY)->toDateString();

        $response = $this->post(route('admin-hr.holidays.store'), [
            'date' => $date,
            'names' => ['New Year']
        ]);

        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('holiday_dates', [
            'name' => 'New Year'
        ]);

        $holiday = HolidayDate::first();
        $this->assertEquals($date, Carbon::parse($holiday->date)->toDateString());

        // Verify that attendance is generated for the active employee since it's a weekday
        $this->assertDatabaseHas('attendances', [
            'nip' => $this->employee->nip,
            'attendance_status' => 'Present',
            'qr_code' => 'SYSTEM-HOLIDAY'
        ]);
    }

    public function test_admin_deleting_holiday_removes_generated_attendances()
    {
        $this->actingAs($this->admin);

        // Next Monday is guaranteed to be a weekday
        $date = Carbon::now()->next(Carbon::MONDAY);
        $dateStr = $date->toDateString();

        // Create holiday
        $holiday = HolidayDate::create([
            'date' => $dateStr,
            'name' => 'Indonesian Independence Day'
        ]);

        // Manually trigger generation or simulate without Eloquent overriding created_at
        \DB::table('attendances')->insert([
            'nip' => $this->employee->nip,
            'check_in' => null,
            'check_out' => null,
            'attendance_status' => 'Present',
            'qr_code' => 'SYSTEM-HOLIDAY',
            'created_at' => $date->copy()->setTime(7, 0, 0),
            'updated_at' => $date->copy()->setTime(7, 0, 0),
        ]);

        $response = $this->delete(route('admin-hr.holidays.destroy', $holiday->id));

        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('holiday_dates', [
            'id' => $holiday->id
        ]);

        // Verify that generated attendance is deleted
        $this->assertDatabaseMissing('attendances', [
            'nip' => $this->employee->nip,
            'qr_code' => 'SYSTEM-HOLIDAY'
        ]);
    }

    public function test_qr_scanning_is_blocked_on_holiday()
    {
        $this->actingAs($this->admin);

        $dateStr = Carbon::today()->toDateString();

        // Create the holiday date directly via DB to prevent SQLite timezone shifting
        \DB::table('holiday_dates')->insert([
            'date' => $dateStr,
            'name' => 'National Holiday',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Simulate a generated holiday attendance
        \DB::table('attendances')->insert([
            'nip' => $this->employee->nip,
            'check_in' => null,
            'check_out' => null,
            'attendance_status' => 'Present',
            'qr_code' => 'SYSTEM-HOLIDAY',
            'created_at' => Carbon::now()->setTime(7, 0, 0),
            'updated_at' => Carbon::now()->setTime(7, 0, 0),
        ]);

        // Try to check-in using QR on a holiday
        $response = $this->post(route('admin-hr.attendance.process-qr'), [
            'qr_data' => 'ATTENSYS:EMP:' . $this->employee->nip
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false
        ]);
    }

    public function test_holiday_does_not_override_existing_permissions()
    {
        $this->actingAs($this->admin);

        // Next Monday is guaranteed to be a weekday
        $date = Carbon::now()->next(Carbon::MONDAY);
        $dateStr = $date->toDateString();

        // Create an approved leave/permission for the employee on next Monday
        Permission::create([
            'nip' => $this->employee->nip,
            'start_date' => $dateStr,
            'completion_date' => $dateStr,
            'type' => 'Leave',
            'permission_status' => 'Approved',
            'information' => 'My Vacation'
        ]);

        // Add attendance record for Permission
        Attendance::create([
            'nip' => $this->employee->nip,
            'attendance_status' => 'Leave',
            'qr_code' => 'SYSTEM',
            'created_at' => $date->copy()->setTime(7, 0, 0),
            'updated_at' => $date->copy()->setTime(7, 0, 0),
        ]);

        // Save holiday
        $response = $this->post(route('admin-hr.holidays.store'), [
            'date' => $dateStr,
            'names' => ['Christmas Day']
        ]);

        $response->assertJson(['success' => true]);

        // The employee should still have the Permission status, not Holiday
        $this->assertDatabaseHas('attendances', [
            'nip' => $this->employee->nip,
            'attendance_status' => 'Leave'
        ]);

        $this->assertDatabaseMissing('attendances', [
            'nip' => $this->employee->nip,
            'qr_code' => 'SYSTEM-HOLIDAY'
        ]);
    }

    public function test_holiday_on_weekend_does_not_generate_attendances()
    {
        $this->actingAs($this->admin);

        // Next Sunday is guaranteed to be a weekend
        $date = Carbon::now()->next(Carbon::SUNDAY)->toDateString();

        $response = $this->post(route('admin-hr.holidays.store'), [
            'date' => $date,
            'names' => ['Sunday Holiday']
        ]);

        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('holiday_dates', [
            'name' => 'Sunday Holiday'
        ]);

        // Verify that NO attendance is generated for the active employee because it's a weekend
        $this->assertDatabaseMissing('attendances', [
            'nip' => $this->employee->nip,
            'qr_code' => 'SYSTEM-HOLIDAY'
        ]);
    }
}
