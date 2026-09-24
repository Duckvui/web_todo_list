<?php

namespace Database\Factories;

use App\Models\TaiKhoan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class TaiKhoanFactory extends Factory
{
    protected $model = TaiKhoan::class;

    public function definition(): array
    {
        return ['tai_khoan' => fake()->unique()->userName().'@gmail.com',
            'mat_khau' => Hash::make('Password!1'), 'trang_thai' => 'hoat_dong'];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (TaiKhoan $user): void {
            $user->thongTinTaiKhoan()->create(['ho_ten' => fake()->name(), 'email' => $user->tai_khoan]);
        });
    }
}
