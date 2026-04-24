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
            'name' => 'Muhammad Faturrahman',
            'email' => 'fatur123@gmail.com',
            'password' => Hash::make('fatur123'),
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
            'name' => 'Muhammad Ivan Febrian',
            'email' => 'ivan123@gmail.com',
            'password' => Hash::make('ivan123'),
            'role' => 'admin_hr',
            'position' => 'HR Manager',
            'division_id' => $division->division_id,
            'no_hp' => '081234567891',
            'alamat' => 'Gedung HR Lt. 1',
            'status' => 'Aktif',
        ]);

         Employee::create([
            'nip' => '00003',
            'name' => 'Nur Iliyanie ',
            'email' => 'lily123@gmail.com',
            'password' => Hash::make('lily123'),
            'role' => 'admin_hr',
            'position' => 'HR Manager',
            'division_id' => $division->division_id,
            'no_hp' => '081234567891',
            'alamat' => 'Gedung HR Lt. 2',
            'status' => 'Aktif',
        ]);

        // 4. Create Karyawan
        Employee::create([
            'nip' => '10001',
            'name' => 'Zahrah Athirah Baddiah',
            'email' => 'zahrah123@gmail.com',
            'password' => Hash::make('zahrah123'),
            'role' => 'karyawan',
            'position' => 'Software Engineer',
            'division_id' => $division->division_id,
            'no_hp' => '081234567892',
            'alamat' => 'Jakarta Selatan',
            'status' => 'Aktif',
        ]);
    }
} 
