<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->string('nip', 7);
            $table->datetime('check_in')->nullable();
            $table->datetime('check_out')->nullable();
            $table->enum('attendance_status', ['Present', 'Late', 'Permission', 'Absent']);
            $table->text('qr_code');
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
