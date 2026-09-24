<?php

namespace Database\Factories;

use App\Models\LichSuViecLam;
use App\Models\TaiKhoan;
use App\Models\ViecLam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LichSuViecLam>
 */
class LichSuViecLamFactory extends Factory
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
            'nguoi_thuc_hien_id' => TaiKhoan::factory(),
            'hanh_dong' => 'cap_nhat',
            'truong_thay_doi' => 'trang_thai',
            'gia_tri_cu' => ['trang_thai' => 'chua_lam'],
            'gia_tri_moi' => ['trang_thai' => 'dang_lam'],
        ];
    }
}
