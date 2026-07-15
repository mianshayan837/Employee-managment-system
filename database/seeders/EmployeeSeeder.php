<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::factory()
            ->count(40)
            ->state(new Sequence(
                fn ($sequence) => [
                    // Cycles through 70 different real-looking placeholder photos
                    'profile_image' => 'https://i.pravatar.cc/300?img='.(($sequence->index % 70) + 1),
                ]
            ))
            ->create();
    }
}