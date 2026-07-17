<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedInteger('annual_leave_balance')->default(14)->after('status');
            $table->unsignedInteger('sick_leave_balance')->default(10)->after('annual_leave_balance');
            $table->unsignedInteger('casual_leave_balance')->default(7)->after('sick_leave_balance');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['annual_leave_balance', 'sick_leave_balance', 'casual_leave_balance']);
        });
    }
};