<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter enum to add Scanner Device role
        DB::statement("ALTER TABLE employees MODIFY COLUMN role ENUM('Super Admin', 'Admin HR', 'Employee', 'Scanner Device') NOT NULL");

        // Seed the Scanner Device account
        $it = \App\Models\Division::where('division_name', 'like', '%IT%')->first();
        if (!$it) {
            $it = \App\Models\Division::first();
        }
        
        if ($it) {
            \App\Models\Employee::firstOrCreate(['nip' => '9999999'], [
                'name' => 'Scanner Device Lobby 1',
                'email' => 'scanner@attensys.com',
                'password' => \Illuminate\Support\Facades\Hash::make('scanner123'),
                'role' => 'Scanner Device',
                'position' => 'Scanner Device',
                'division_id' => $it->division_id,
                'no_hp' => '081234567899',
                'alamat' => 'Lobby Utama',
                'status' => 'Aktif',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete scanner device account first
        \App\Models\Employee::where('nip', '9999999')->delete();

        // Revert enum
        DB::statement("ALTER TABLE employees MODIFY COLUMN role ENUM('Super Admin', 'Admin HR', 'Employee') NOT NULL");
    }
};
