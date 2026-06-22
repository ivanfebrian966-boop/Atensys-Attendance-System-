<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            'IT' => Division::create(['division_name' => 'IT Division']),
            'HR' => Division::create(['division_name' => 'HR Division']),
            'Operations' => Division::create(['division_name' => 'Operations Division']),
        ];

        $raw_data = [
            ['nama' => 'Ahmad Rafi` Sa`id Fadhilah', 'jk' => 'L', 'wa' => '082392573749'],
            ['nama' => 'Akbar Zamroni', 'jk' => 'L', 'wa' => '08117092501'],
            ['nama' => 'Cahyati Lamona Sitohang', 'jk' => 'P', 'wa' => '081266620445'],
            ['nama' => 'Crist Garcia Pasaribu', 'jk' => 'L', 'wa' => '089623467477'],
            ['nama' => 'Damar Widi Nugroho', 'jk' => 'L', 'wa' => '082169784529'],
            ['nama' => 'Dias Ferdian', 'jk' => 'L', 'wa' => '085761021014'],
            ['nama' => 'Dimas Cakra Surya Ananta', 'jk' => 'L', 'wa' => '085278458109'],
            ['nama' => 'Fathur Alfitrah Dermawan', 'jk' => 'L', 'wa' => '089635803536'],
            ['nama' => 'Fazri Rahman', 'jk' => 'L', 'wa' => '085264312600'],
            ['nama' => 'Fenni Patrik Simanjuntak', 'jk' => 'P', 'wa' => '087767351842'],
            ['nama' => 'Haikal Mubaroq Zafia', 'jk' => 'L', 'wa' => '082170533270'],
            ['nama' => 'Hilda Tri Utami', 'jk' => 'P', 'wa' => '087770703580'],
            ['nama' => 'M Nurramadhan Irsya', 'jk' => 'L', 'wa' => '085278342106'],
            ['nama' => 'M. Luthfi Causart Azavi', 'jk' => 'L', 'wa' => '081261408239'],
            ['nama' => 'Michael Sando Turnip', 'jk' => 'L', 'wa' => '081372079904'],
            ['nama' => 'Muhammad Faturrahman', 'jk' => 'L', 'wa' => '085823310134'],
            ['nama' => 'Muhammad Ivan Febrian', 'jk' => 'L', 'wa' => '082008982186'],
            ['nama' => 'Muradika Laksamana Putra', 'jk' => 'L', 'wa' => '089521953535'],
            ['nama' => 'Nur Iliyanie', 'jk' => 'P', 'wa' => '088271477576'],
            ['nama' => 'Rangga Surya Saputra', 'jk' => 'L', 'wa' => '081261260195'],
            ['nama' => 'Reifandra Kinadi', 'jk' => 'L', 'wa' => '081534211742'],
            ['nama' => 'Robi Yahya Harahap', 'jk' => 'L', 'wa' => '087754460586'],
            ['nama' => 'Shofiyyah Binti Tholib Uwaini', 'jk' => 'P', 'wa' => '085761801593'],
            ['nama' => 'Siti Halimah Chania', 'jk' => 'P', 'wa' => '085136863353'],
            ['nama' => 'Yohanes Armando Hubin', 'jk' => 'L', 'wa' => '083802617114'],
            ['nama' => 'Zahrah Athirah Badiah', 'jk' => 'P', 'wa' => '081268721870'],
            ['nama' => 'Zaid Hasbiya Abrar', 'jk' => 'L', 'wa' => '081261442840'],
        ];

        $empCounter = 1;

        foreach ($raw_data as $emp) {
            $isSuperAdmin = $emp['nama'] === 'Muhammad Faturrahman';
            $isAdminHR = $emp['nama'] === 'Nur Iliyanie';

            $role = 'Employee';
            $nip = 'EMP-' . str_pad($empCounter++, 3, '0', STR_PAD_LEFT);
            $division_id = $divisions['Operations']->division_id;
            $position = 'Staff';

            if ($isSuperAdmin) {
                $role = 'Super Admin';
                $nip = 'SUP-001';
                $division_id = $divisions['IT']->division_id;
                $position = 'System Administrator';
            } elseif ($isAdminHR) {
                $role = 'Admin HR';
                $nip = 'HRD-001';
                $division_id = $divisions['HR']->division_id;
                $position = 'HR Manager';
            }

            if ($isSuperAdmin) {
                $email = 'admin@attensys.com';
                $password = 'admin123';
            } else {
                $cleanName = strtolower(str_replace([' ', '`', '.', '\''], '', $emp['nama']));
                $email = $cleanName . '@attensys.com';
                $password = $cleanName . '123';
            }

            Employee::create([
                'nip' => $nip,
                'name' => $emp['nama'],
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'position' => $position,
                'division_id' => $division_id,
                'no_hp' => $emp['wa'],
                'alamat' => 'Batam',
                'status' => 'Aktif',
                'gender' => $emp['jk'] === 'L' ? 'Male' : 'Female',
            ]);
        }
    }
}