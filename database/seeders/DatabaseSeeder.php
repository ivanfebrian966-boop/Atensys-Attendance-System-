<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Division
        $division = Division::create([
            'division_name' => 'IT Department',
        ]);

        // 2. Create Super Admin
        Employee::create([
            'nip' => '00001',
            'name' => 'Super Admin',
            'email' => 'superadmin@attensys.id',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'position' => 'System Administrator',
            'division_id' => $division->division_id,
            'no_hp' => '081234567890',
            'alamat' => 'Kantor Pusat ATTENSYS',
            'status' => 'Aktif',
        ]);

        // 3. Create Admin HR
        Employee::create([
            'nip' => '00002',
            'name' => 'HR Manager',
            'email' => 'adminhr@attensys.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_hr',
            'position' => 'HR Manager',
            'division_id' => $division->division_id,
            'no_hp' => '081234567891',
            'alamat' => 'Gedung HR Lt. 1',
            'status' => 'Aktif',
        ]);

        // 4. Create Karyawan
        Employee::create([
            'nip' => '10001',
            'name' => 'Ivan Febrian',
            'email' => 'karyawan@attensys.id',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'position' => 'Software Engineer',
            'division_id' => $division->division_id,
            'no_hp' => '081234567892',
            'alamat' => 'Jakarta Selatan',
            'status' => 'Aktif',
        ]);
    }
}
