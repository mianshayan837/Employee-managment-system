<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        $designations = [
            'Software Engineer', 'Senior Software Engineer', 'HR Executive',
            'Sales Associate', 'Accountant', 'Marketing Specialist',
            'Operations Coordinator', 'Team Lead', 'Product Manager',
        ];

        return [
            'employee_code' => 'EMP-'.strtoupper(Str::random(5)),
            'department_id' => Department::inRandomOrder()->first()?->id ?? Department::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('+92 3## #######'),
            'profile_image' => null,
            'designation' => fake()->randomElement($designations),
            'salary' => fake()->numberBetween(35000, 220000),
            'joining_date' => fake()->dateTimeBetween('-5 years', 'now'),
            'status' => fake()->randomElement(['active', 'active', 'active', 'inactive']),
        ];
    }
}
