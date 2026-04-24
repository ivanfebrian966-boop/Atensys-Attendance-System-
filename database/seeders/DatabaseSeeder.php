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
        $data = [
            'HR' => [
                ['nama' => 'Andi Saputra', 'posisi' => 'HR Manager'],
                ['nama' => 'Siti Nurhaliza', 'posisi' => 'HR Staff'],
            ],
            'Finance' => [
                ['nama' => 'Budi Santoso', 'posisi' => 'Finance Manager'],
                ['nama' => 'Dewi Lestari', 'posisi' => 'Accountant'],
            ],
            'IT' => [
                ['nama' => 'Rizky Pratama', 'posisi' => 'Software Developer'],
                ['nama' => 'Agus Setiawan', 'posisi' => 'Network Engineer'],
            ],
            'Marketing' => [
                ['nama' => 'Fajar Nugroho', 'posisi' => 'Digital Marketing Specialist'],
                ['nama' => 'Lina Marlina', 'posisi' => 'Content Creator'],
            ],
            'Sales' => [
                ['nama' => 'Hendra Wijaya', 'posisi' => 'Sales Executive'],
                ['nama' => 'Rina Sari', 'posisi' => 'Account Executive'],
            ],
            'Operations' => [
                ['nama' => 'Dedi Kurniawan', 'posisi' => 'Operations Manager'],
                ['nama' => 'Joko Susilo', 'posisi' => 'Operator'],
            ],
            'Logistics' => [
                ['nama' => 'Eko Prasetyo', 'posisi' => 'Warehouse Staff'],
                ['nama' => 'Tono Sapriadi', 'posisi' => 'Driver'],
            ],
            'Customer Service' => [
                ['nama' => 'Maya Putri', 'posisi' => 'Customer Service Officer'],
                ['nama' => 'Intan Permata', 'posisi' => 'Call Center Agent'],
            ],
            'Legal' => [
                ['nama' => 'Arif Rahman', 'posisi' => 'Legal Officer'],
                ['nama' => 'Putri Ayu', 'posisi' => 'Contract Specialist'],
            ],
            'R&D' => [
                ['nama' => 'Yoga Pratama', 'posisi' => 'Researcher'],
                ['nama' => 'Nina Oktaviani', 'posisi' => 'Product Developer'],
            ],
            'Administration' => [
                ['nama' => 'Slamet Riyadi', 'posisi' => 'Admin Staff'],
                ['nama' => 'Dina Safitri', 'posisi' => 'Document Controller'],
            ],
            'Procurement' => [
                ['nama' => 'Bayu Saputra', 'posisi' => 'Purchasing Staff'],
                ['nama' => 'Vina Oktavia', 'posisi' => 'Vendor Specialist'],
            ],
            'Engineering' => [
                ['nama' => 'Rudi Hartono', 'posisi' => 'Mechanical Engineer'],
                ['nama' => 'Ahmad Fauzi', 'posisi' => 'Electrical Engineer'],
            ],
            'Quality Assurance' => [
                ['nama' => 'Sari Dewi', 'posisi' => 'QA Staff'],
                ['nama' => 'Teguh Prakoso', 'posisi' => 'Quality Auditor'],
            ],
            'Security & Safety' => [
                ['nama' => 'Yusuf Hidayat', 'posisi' => 'Security Staff'],
                ['nama' => 'Rahmat Hidayat', 'posisi' => 'Safety Officer'],
            ],
            'Corporate Strategy' => [
                ['nama' => 'Kevin Alexander', 'posisi' => 'Business Analyst'],
                ['nama' => 'Claudia Angel', 'posisi' => 'Data Analyst'],
            ],
        ];

        $nip = 1;

        foreach ($data as $divisionName => $employees) {

            $division = Division::create([
                'division_name' => $divisionName . ' Division',
            ]);

            foreach ($employees as $emp) {

                $clean = strtolower(str_replace(' ', '', $emp['posisi']));

                Employee::create([
                    'nip' => str_pad($nip++, 5, '0', STR_PAD_LEFT),
                    'name' => $emp['nama'],
                    'email' => $clean . '@attensys.com',
                    'password' => Hash::make($clean . '123'),
                    'role' => $emp['posisi'] == 'HR Manager' ? 'admin_hr' : 'karyawan',
                    'position' => $emp['posisi'],
                    'division_id' => $division->division_id,
                    'no_hp' => '08' . rand(1000000000, 9999999999),
                    'alamat' => 'Kantor ' . $divisionName,
                    'status' => 'Aktif',
                ]);
            }
        }

        // SUPER ADMIN
        $it = Division::where('division_name', 'IT Division')->first();

        Employee::create([
            'nip' => '00000',
            'name' => 'Muhammad Faturrahman',
            'email' => 'admin@attensys.com',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'position' => 'System Administrator',
            'division_id' => $it->division_id,
            'no_hp' => '081234567890',
            'alamat' => 'Kantor Pusat',
            'status' => 'Aktif',
        ]);
    }
}