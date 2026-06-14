<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_holiday_dates_table + add_holiday_status_to_attendances
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel holiday_dates
        Schema::create('holiday_dates', function (Blueprint $table) {
            $table->id('holiday_id');
            $table->date('date');
            // Nama hari libur disimpan sebagai string (jika > 1 nama per tanggal, akan menjadi baris baru)
            $table->string('name', 60);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });

        // 2. Tambahkan status 'Holiday' ke enum attendance_status (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            // Cek apakah 'Holiday' sudah ada di enum; jika tidak, tambahkan
            $columnType = DB::select(
                "SELECT COLUMN_TYPE FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = 'attendances'
                   AND COLUMN_NAME = 'attendance_status'"
            );
            if (!empty($columnType) && !str_contains($columnType[0]->COLUMN_TYPE, 'Holiday')) {
                DB::statement(
                    "ALTER TABLE `attendances`
                     MODIFY COLUMN `attendance_status`
                     ENUM('Present','Late','Permission','Absent','Holiday') NOT NULL"
                );
            }
        }
    }

    public function down(): void
    {
        // 2. Kembalikan enum (hapus Holiday)
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "UPDATE `attendances` SET `attendance_status` = 'Absent'
                 WHERE `attendance_status` = 'Holiday'"
            );
            DB::statement(
                "ALTER TABLE `attendances`
                 MODIFY COLUMN `attendance_status`
                 ENUM('Present','Late','Permission','Absent') NOT NULL"
            );
        }

        // 1. Drop tabel holiday_dates
        Schema::dropIfExists('holiday_dates');
    }
};
