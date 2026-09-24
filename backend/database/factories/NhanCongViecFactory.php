<?php

namespace Database\Factories;

use App\Models\NhanCongViec;
use App\Models\TaiKhoan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NhanCongViec>
 */
class NhanCongViecFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tai_khoan_id' => TaiKhoan::factory(),
            'ten' => fake()->unique()->word(),
            'mau_sac' => fake()->hexColor(),
        ];
    }
}
