<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Engineering', 'code' => 'ENG', 'description' => 'Product & platform engineering.'],
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'People operations and hiring.'],
            ['name' => 'Sales', 'code' => 'SAL', 'description' => 'Business development and client accounts.'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Accounting, payroll and budgeting.'],
            ['name' => 'Marketing', 'code' => 'MKT', 'description' => 'Brand, growth and communications.'],
            ['name' => 'Operations', 'code' => 'OPS', 'description' => 'Facilities and day-to-day operations.'],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['code' => $department['code']], $department);
        }
    }
}
