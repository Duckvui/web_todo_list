<?php

namespace Database\Factories;

use App\Models\NhiemVuNho;
use App\Models\ViecLam;
use Illuminate\Database\Eloquent\Factories\Factory;

class NhiemVuNhoFactory extends Factory
{
    protected $model = NhiemVuNho::class;

    public function definition(): array
    {
        return [
            'viec_lam_id' => ViecLam::factory(),
            'tieu_de' => fake()->sentence(4),
            'da_hoan_thanh' => false,
            'thu_tu' => 0,
            'hoan_thanh_luc' => null,
        ];
    }

    public function hoanThanh(): static
    {
        return $this->state(fn (): array => [
            'da_hoan_thanh' => true,
            'hoan_thanh_luc' => now(),
        ]);
    }
}
