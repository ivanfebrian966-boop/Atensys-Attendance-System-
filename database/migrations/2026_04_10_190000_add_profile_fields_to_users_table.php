<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('division');
            }
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'join_date')) {
                $table->date('join_date')->nullable()->after('position');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('join_date');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('bio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['phone', 'position', 'join_date', 'address', 'bio', 'avatar'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
