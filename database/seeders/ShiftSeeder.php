<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        Shift::firstOrCreate(
            ['name' => 'Morning Shift'],
            [
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'grace_minutes' => 15,
            ]
        );

        Shift::firstOrCreate(
            ['name' => 'Evening Shift'],
            [
                'start_time' => '13:00:00',
                'end_time' => '17:00:00',
                'grace_minutes' => 15,
            ]
        );
    }
}