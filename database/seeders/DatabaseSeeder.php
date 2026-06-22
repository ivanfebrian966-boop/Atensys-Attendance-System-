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
            'OPR' => Division::create(['division_name' => 'Operations Division']),
            'FIN' => Division::create(['division_name' => 'Finance Division']),
            'MKT' => Division::create(['division_name' => 'Marketing Division']),
            'SLS' => Division::create(['division_name' => 'Sales Division']),
            'LOG' => Division::create(['division_name' => 'Logistics Division']),
            'CS'  => Division::create(['division_name' => 'Customer Service Division']),
            'ADM' => Division::create(['division_name' => 'Administration Division']),
            'ENG' => Division::create(['division_name' => 'Engineering Division']),
        ];

        // Counter for NIP and Email per division
        $counters = [
            'IT' => 1,
            'HRD' => 1, // HRD for HR Division
            'OPR' => 1,
            'FIN' => 1,
            'MKT' => 1,
            'SLS' => 1,
            'LOG' => 1,
            'CS'  => 1,
            'ADM' => 1,
            'ENG' => 1,
        ];

        $raw_data = [
            ['nama' => 'Ahmad Rafi` Sa`id Fadhilah', 'jk' => 'L', 'wa' => '082392573749', 'div' => 'OPR'],
            ['nama' => 'Akbar Zamroni', 'jk' => 'L', 'wa' => '08117092501', 'div' => 'FIN'],
            ['nama' => 'Cahyati Lamona Sitohang', 'jk' => 'P', 'wa' => '081266620445', 'div' => 'MKT'],
            ['nama' => 'Crist Garcia Pasaribu', 'jk' => 'L', 'wa' => '089623467477', 'div' => 'IT'],
            ['nama' => 'Damar Widi Nugroho', 'jk' => 'L', 'wa' => '082169784529', 'div' => 'SLS'],
            ['nama' => 'Dias Ferdian', 'jk' => 'L', 'wa' => '085761021014', 'div' => 'LOG'],
            ['nama' => 'Dimas Cakra Surya Ananta', 'jk' => 'L', 'wa' => '085278458109', 'div' => 'CS'],
            ['nama' => 'Fathur Alfitrah Dermawan', 'jk' => 'L', 'wa' => '089635803536', 'div' => 'ADM'],
            ['nama' => 'Fazri Rahman', 'jk' => 'L', 'wa' => '085264312600', 'div' => 'ENG'],
            ['nama' => 'Fenni Patrik Simanjuntak', 'jk' => 'P', 'wa' => '087767351842', 'div' => 'FIN'],
            ['nama' => 'Haikal Mubaroq Zafia', 'jk' => 'L', 'wa' => '082170533270', 'div' => 'MKT'],
            ['nama' => 'Hilda Tri Utami', 'jk' => 'P', 'wa' => '087770703580', 'div' => 'SLS'],
            ['nama' => 'M Nurramadhan Irsya', 'jk' => 'L', 'wa' => '085278342106', 'div' => 'OPR'],
            ['nama' => 'M. Luthfi Causart Azavi', 'jk' => 'L', 'wa' => '081261408239', 'div' => 'LOG'],
            ['nama' => 'Michael Sando Turnip', 'jk' => 'L', 'wa' => '081372079904', 'div' => 'CS'],
            ['nama' => 'Muhammad Faturrahman', 'jk' => 'L', 'wa' => '085823310134', 'div' => 'IT'],
            ['nama' => 'Muhammad Ivan Febrian', 'jk' => 'L', 'wa' => '082008982186', 'div' => 'ADM'],
            ['nama' => 'Muradika Laksamana Putra', 'jk' => 'L', 'wa' => '089521953535', 'div' => 'ENG'],
            ['nama' => 'Nur Iliyanie', 'jk' => 'P', 'wa' => '088271477576', 'div' => 'HRD'],
            ['nama' => 'Rangga Surya Saputra', 'jk' => 'L', 'wa' => '081261260195', 'div' => 'SLS'],
            ['nama' => 'Reifandra Kinadi', 'jk' => 'L', 'wa' => '081534211742', 'div' => 'IT'],
            ['nama' => 'Robi Yahya Harahap', 'jk' => 'L', 'wa' => '087754460586', 'div' => 'OPR'],
            ['nama' => 'Shofiyyah Binti Tholib Uwaini', 'jk' => 'P', 'wa' => '085761801593', 'div' => 'FIN'],
            ['nama' => 'Siti Halimah Chania', 'jk' => 'P', 'wa' => '085136863353', 'div' => 'MKT'],
            ['nama' => 'Yohanes Armando Hubin', 'jk' => 'L', 'wa' => '083802617114', 'div' => 'ENG'],
            ['nama' => 'Zahrah Athirah Badiah', 'jk' => 'P', 'wa' => '081268721870', 'div' => 'CS'],
            ['nama' => 'Zaid Hasbiya Abrar', 'jk' => 'L', 'wa' => '081261442840', 'div' => 'LOG'],
        ];

        foreach ($raw_data as $emp) {
            $isSuperAdmin = $emp['nama'] === 'Muhammad Faturrahman';
            $isAdminHR = $emp['nama'] === 'Nur Iliyanie';

            // Determine division key and prefix
            $divKey = $emp['div'];
            $prefix = $divKey;

            // Mapping for HR
            if ($divKey === 'HRD') {
                $division_id = $divisions['HR']->division_id;
            } else {
                $division_id = $divisions[$divKey]->division_id;
            }

            $role = 'Employee';
            $position = 'Staff';

            if ($isSuperAdmin) {
                $role = 'Super Admin';
                $position = 'System Administrator';
            } elseif ($isAdminHR) {
                $role = 'Admin HR';
                $position = 'HR Manager';
            }

            // Get current counter for this division
            $currentCount = $counters[$prefix]++;
            
            // Generate NIP: Prefix-00X
            $nip = $prefix . '-' . str_pad($currentCount, 3, '0', STR_PAD_LEFT);

            // Generate Email and Password
            if ($isSuperAdmin) {
                $email = 'admin@attensys.com';
                $password = 'admin123';
            } else {
                // Email format: divisionname + counter @attensys.com (e.g. operations1@attensys.com)
                $divisionNameLower = strtolower(str_replace(' Division', '', $divisions[$divKey === 'HRD' ? 'HR' : $divKey]->division_name));
                $email = str_replace(' ', '', $divisionNameLower) . $currentCount . '@attensys.com';
                $password = str_replace(' ', '', $divisionNameLower) . '123';
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