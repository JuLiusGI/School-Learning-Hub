<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@bobonb.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::factory()->create([
            'name' => 'Teacher User',
            'email' => 'teacher@bobonb.local',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'status' => 'active',
        ]);

        SchoolYear::updateOrCreate(
            ['name' => '2025-2026'],
            [
                'start_date' => '2025-06-01',
                'end_date' => '2026-03-31',
                'is_active' => true,
            ]
        );

        foreach (range(1, 6) as $level) {
            Grade::updateOrCreate(
                ['level' => 'Grade '.$level],
                ['level' => 'Grade '.$level]
            );
        }
    }
}
