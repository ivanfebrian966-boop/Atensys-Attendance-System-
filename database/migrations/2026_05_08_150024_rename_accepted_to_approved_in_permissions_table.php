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
        DB::table('permissions')->where('status', 'Accepted')->update(['status' => 'Approved']);
        DB::table('attendances')->where('status', 'Accepted')->update(['status' => 'Approved']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->where('status', 'Approved')->update(['status' => 'Accepted']);
        DB::table('attendances')->where('status', 'Approved')->update(['status' => 'Accepted']);
    }
};
