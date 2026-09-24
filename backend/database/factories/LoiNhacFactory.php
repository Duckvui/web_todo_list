<?php

namespace Database\Factories;

use App\Models\LoiNhac;
use App\Models\ViecLam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoiNhac>
 */
class LoiNhacFactory extends Factory
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
            'thoi_gian_nhac' => fake()->dateTimeBetween('now', '+1 month'),
            'phuong_thuc' => fake()->randomElement(['trong_ung_dung', 'email']),
            'trang_thai' => 'cho_gui',
            'noi_dung' => fake()->sentence(),
            'da_gui_luc' => null,
        ];
    }
}
