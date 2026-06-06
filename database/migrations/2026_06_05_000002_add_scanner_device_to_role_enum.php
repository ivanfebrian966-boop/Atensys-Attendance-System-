<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the scanner_devices table (separate entity from employees)
     * and seeds the first device SD-09.
     */
    public function up(): void
    {
        // 1. Revert the employees role enum back to original (no Scanner Device)
        DB::statement("ALTER TABLE employees MODIFY COLUMN role ENUM('Super Admin', 'Admin HR', 'Employee') NOT NULL");

        // 2. Delete the wrongly-seeded scanner employee if it exists
        DB::table('employees')->where('nip', '9999999')->delete();

        // 3. Create scanner_devices table
        Schema::create('scanner_devices', function (Blueprint $table) {
            $table->string('scanner_id', 5)->primary();
            $table->string('password', 60);
            $table->timestamps();
        });

        // 4. Seed the first scanner device
        DB::table('scanner_devices')->insert([
            'scanner_id' => 'SD-09',
            'password'   => \Illuminate\Support\Facades\Hash::make('scanner123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scanner_devices');

        // Restore enum with Scanner Device (as it was before this migration)
        DB::statement("ALTER TABLE employees MODIFY COLUMN role ENUM('Super Admin', 'Admin HR', 'Employee', 'Scanner Device') NOT NULL");
    }
};
