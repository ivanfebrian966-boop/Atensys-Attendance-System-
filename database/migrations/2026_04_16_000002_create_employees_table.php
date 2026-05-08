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
        Schema::create('employees', function (Blueprint $table) {
            $table->string('nip', 7)->primary();
            $table->string('password', 255);
            $table->string('name', 100);
            $table->enum('role', ['Super Admin', 'Admin HR', 'Employee']);
            $table->string('position', 100);
            $table->string('email', 100)->unique();
            $table->unsignedBigInteger('division_id');
            $table->string('no_hp', 15);
            $table->text('alamat');
            $table->enum('status', ['Aktif', 'Tidak Aktif',])->default('Aktif');
            $table->timestamps();

            $table->foreign('division_id')->references('division_id')->on('divisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
