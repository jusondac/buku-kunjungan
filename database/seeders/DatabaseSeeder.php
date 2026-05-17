<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guest;
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
        // Create a staff user (petugas) for testing - only if not exists
        User::firstOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'name' => 'Petugas Admin',
                'password' => Hash::make('password123'),
            ]
        );

        // Create sample guests for testing (50-70 records with realistic durations)
        // GuestFactory already handles purpose_lainnya when purpose is 'lainnya'.
        $guestCount = random_int(50, 70);
        Guest::factory($guestCount)->create();
    }
}

