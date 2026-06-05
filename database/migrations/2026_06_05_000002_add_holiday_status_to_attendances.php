<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menambahkan nilai 'Holiday' ke kolom enum attendance_status
     * pada tabel attendances (MySQL: ALTER TABLE ... MODIFY COLUMN).
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `attendances` MODIFY COLUMN `attendance_status` ENUM('Present','Late','Permission','Absent','Holiday') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Hapus Holiday, kembalikan ke semula
            // (hapus dulu data Holiday agar tidak error)
            DB::statement("UPDATE `attendances` SET `attendance_status` = 'Absent' WHERE `attendance_status` = 'Holiday'");
            DB::statement("ALTER TABLE `attendances` MODIFY COLUMN `attendance_status` ENUM('Present','Late','Permission','Absent') NOT NULL");
        }
    }
};
