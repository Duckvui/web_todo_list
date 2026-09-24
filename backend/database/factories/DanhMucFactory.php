<?php

namespace Database\Factories;

use App\Models\DanhMuc;
use App\Models\TaiKhoan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DanhMuc>
 */
class DanhMucFactory extends Factory
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
            'ten' => fake()->unique()->words(2, true),
            'mau_sac' => fake()->hexColor(),
        ];
    }
}
