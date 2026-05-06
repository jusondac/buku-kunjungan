<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'purpose' => $selectedKeperluan,
            'purpose_lainnya' => $selectedKeperluan === 'lainnya' 
                ? $this->faker->randomElement($customKperluanValues)
                : null,
            'status' => $this->faker->randomElement(['menunggu', 'dilayani', 'selesai']),
        ];
    }
}
