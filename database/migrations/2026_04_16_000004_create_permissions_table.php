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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id('permission_id');
            $table->string('nip', 7);
            $table->enum('type', ['Sick', 'Leave']);
            $table->enum('leave_category', [
                'Marriage Leave',
                'Maternity Leave',
                'Annual Leave',
                'Bereavement Leave',
                'Personal Leave',
                'Family Event',
                'Others',
            ])->nullable();
            $table->enum('sick_category', [
                'Sick Leave with Medical Certificate',
                'Hospitalization',
                'Accident',
                'Mild Illness (Flu / Fever)',
                'Outpatient Care',
                'Medical Checkup',
                'Others',
            ])->nullable();
            $table->enum('permission_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('reject_reason')->nullable();
            $table->text('information');
            $table->string('file')->nullable();
            $table->date('start_date');
            $table->date('completion_date');
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
