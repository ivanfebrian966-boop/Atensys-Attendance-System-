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
        // Check if attendances table exists and has old structure
        if (Schema::hasTable('attendances')) {
            // Check if it has the old employee_id column
            if (Schema::hasColumn('attendances', 'employee_id')) {
                // Drop foreign keys if exist
                Schema::table('attendances', function (Blueprint $table) {
                    // Drop all indexes first
                    $table->dropIndexIfExists('attendances_employee_id_date_index');
                    $table->dropIndexIfExists('attendances_date_index');
                    $table->dropIndexIfExists('attendances_division_index');
                    $table->dropIndexIfExists('attendances_status_index');
                });
                
                // Drop and recreate table with correct structure
                Schema::drop('attendances');
            }
        }
        
        // Create correct attendances table
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->date('date');
                $table->dateTime('check_in')->nullable();
                $table->dateTime('check_out')->nullable();
                $table->enum('status', ['Present', 'Absent', 'Late', 'Sick', 'Permission'])->default('Present');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                // Foreign key
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                
                // Indexes
                $table->index(['user_id', 'date']);
                $table->index('date');
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
