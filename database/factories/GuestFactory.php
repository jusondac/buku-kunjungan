<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guest>
 */
class GuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kperluanOptions = [
            'rehabilitas',
            'skhpn',
            'bagian umum',
            'pemberantasan',
            'lainnya',
        ];
        
        $selectedKeperluan = $this->faker->randomElement($kperluanOptions);
        
        // Custom values for "lainnya"
        $customKperluanValues = [
            'Konsultasi khusus',
            'Keperluan pribadi',
            'Visitasi lapangan',
            'Rapat koordinasi',
            'Pertemuan formal',
            'Audit internal',
            'Pelatihan staf',
            'Diskusi program',
            'Evaluasi kegiatan',
            'Jaminan kualitas',
        ];
        
        // Distribute created_at across different time ranges
        $timeRange = $this->faker->randomElement([
            'today',
            'last_7_days',
            'last_30_days',
            'last_6_months',
            'last_year',
        ]);
        
        $createdAt = match($timeRange) {
            'today' => now()->subHours($this->faker->numberBetween(0, 23))->subMinutes($this->faker->numberBetween(0, 59)),
            'last_7_days' => now()->subDays($this->faker->numberBetween(0, 7)),
            'last_30_days' => now()->subDays($this->faker->numberBetween(7, 30)),
            'last_6_months' => now()->subDays($this->faker->numberBetween(30, 180)),
            'last_year' => now()->subDays($this->faker->numberBetween(180, 365)),
            default => now(),
        };
        
        // Random status with 60% selesai, 25% dilayani, 15% menunggu
        $randomValue = $this->faker->numberBetween(1, 100);
        if ($randomValue <= 60) {
            $status = 'selesai';
        } elseif ($randomValue <= 85) {
            $status = 'dilayani';
        } else {
            $status = 'menunggu';
        }
        
        // Generate random duration (1-60 minutes)
        $durationMinutes = $this->faker->numberBetween(1, 60);
        $durationSeconds = $durationMinutes * 60;
        
        // Calculate completed_at if selesai
        $completedAt = null;
        if ($status === 'selesai') {
            $completedAt = $createdAt->copy()->addMinutes($durationMinutes);
        }
        
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->optional(0.7)->email(),
            'phone' => $this->faker->numerify('08##########'),
            'address' => $this->faker->address(),
            'purpose' => $selectedKeperluan,
            'purpose_lainnya' => $selectedKeperluan === 'lainnya' 
                ? $this->faker->randomElement($customKperluanValues)
                : null,
            'status' => $status,
            'duration_seconds' => $durationSeconds,
            'completed_at' => $completedAt,
            'created_at' => $createdAt,
            'updated_at' => $completedAt ?? $createdAt,
        ];
    }
}

