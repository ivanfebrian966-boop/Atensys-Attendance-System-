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
            $table->string('nip')->primary();
            $table->string('password');
            $table->string('name');
            $table->string('role');
            $table->string('position');
            $table->string('email')->unique();
            $table->unsignedBigInteger('division_id');
            $table->string('no_hp');
            $table->text('alamat');
            $table->string('status')->default('Aktif');
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
