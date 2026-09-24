<?php

namespace Database\Factories;

use App\Models\ChiaSeViecLam;
use App\Models\TaiKhoan;
use App\Models\ViecLam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChiaSeViecLam>
 */
class ChiaSeViecLamFactory extends Factory
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
            'tai_khoan_id' => TaiKhoan::factory(),
            'quyen' => fake()->randomElement(['xem', 'chinh_sua']),
            'trang_thai' => 'cho_phan_hoi',
            'chap_nhan_luc' => null,
        ];
    }
}
