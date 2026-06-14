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
        Schema::create('genders', function (Blueprint $table) {
            $table->id('gender_id');
            $table->string('gender_name', 30);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });

        DB::table('genders')->insert([
            ['gender_name' => 'Man', 'created_at' => now()],
            ['gender_name' => 'Woman', 'created_at' => now()],
        ]);

        Schema::create('employees', function (Blueprint $table) {
            $table->string('nip', 7)->primary();
            $table->string('password', 60);
            $table->string('name', 75);
            $table->unsignedBigInteger('gender_id')->default(1);
            $table->enum('role', ['Super Admin', 'Admin HR', 'Employee']);
            $table->string('position', 30);
            $table->string('email', 50)->unique();
            $table->unsignedBigInteger('division_id');
            $table->string('no_hp', 15);
            $table->text('alamat');
            $table->enum('status', ['Aktif', 'Tidak Aktif',])->default('Aktif');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('gender_id')->references('gender_id')->on('genders')->onDelete('restrict');
            $table->foreign('division_id')->references('division_id')->on('divisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('genders');
    }
};
