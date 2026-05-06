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
        // Create a staff user (petugas) for testing
        User::factory()->create([
            'name' => 'Petugas Admin',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Create sample guests for testing (50-100 records)
        Guest::factory(75)->create();
    }
}

