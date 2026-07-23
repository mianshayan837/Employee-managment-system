<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $morningId = Shift::where('name', 'Morning Shift')->value('id');
        $eveningId = Shift::where('name', 'Evening Shift')->value('id');

       
        $shiftAssignments = array_merge(
            array_fill(0, 10, $morningId),
            array_fill(0, 10, $eveningId)
        );
        shuffle($shiftAssignments);

        Employee::factory()
            ->count(20)
            ->state(new Sequence(
                fn ($sequence) => [
                    'profile_image' => 'https://i.pravatar.cc/300?img='.(($sequence->index % 70) + 1),
                    'shift_id' => $shiftAssignments[$sequence->index],
                ]
            ))
            ->create();
    }
}