<?php

namespace Database\Factories;

use App\Models\TepDinhKem;
use App\Models\ViecLam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TepDinhKem>
 */
class TepDinhKemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'viec_lam_id' => ViecLam::factory(),
            'ten_goc' => fake()->word().'.pdf',
            'duong_dan' => 'tep-dinh-kem/'.fake()->uuid().'.pdf',
            'o_luu_tru' => 'public',
            'loai_mime' => 'application/pdf',
            'kich_thuoc' => fake()->numberBetween(10_000, 5_000_000),
        ];
    }
}
