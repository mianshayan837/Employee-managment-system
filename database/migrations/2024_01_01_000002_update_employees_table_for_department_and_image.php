<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * NOTE: This migration assumes an existing `employees` table with a
     * plain string `department` column (as in the original project).
     * It swaps that column for a proper `department_id` foreign key and
     * adds a `profile_image` column for the uploaded photo.
     *
     * If your `employees` table does not exist yet, create it first with
     * the original fields (name, email, phone, designation, salary,
     * joining_date, status, employee_code) before running this one.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('department_id')
                ->nullable()
                ->after('employee_code')
                ->constrained('departments')
                ->nullOnDelete();

            $table->string('profile_image')->nullable()->after('phone');
        });

        // Drop the old free-text department column if it exists.
        if (Schema::hasColumn('employees', 'department')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('department');
            });
        }
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
            $table->dropColumn('profile_image');
            $table->string('department')->nullable();
        });
    }
};
