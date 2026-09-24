<?php

namespace Database\Factories;

use App\Models\TaiKhoan;
use App\Models\ViecLam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViecLamFactory extends Factory
{
    protected $model = ViecLam::class;

    public function definition(): array
    {
        return [
            'tai_khoan_id' => TaiKhoan::factory(),
            'tieu_de' => fake()->sentence(5),
            'mo_ta' => fake()->optional()->paragraph(),
            'trang_thai' => 'chua_lam',
            'muc_do_hoan_thanh' => 0,
            'muc_do_uu_tien' => fake()->randomElement(['thap', 'trung_binh', 'cao']),
            'han_chot' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'hoan_thanh_luc' => null,
        ];
    }

    public function hoanThanh(): static
    {
        return $this->state(fn (): array => [
            'trang_thai' => 'hoan_thanh',
            'hoan_thanh_luc' => now(),
        ]);
    }
}
