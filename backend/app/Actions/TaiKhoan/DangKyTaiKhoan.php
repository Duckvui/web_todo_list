<?php

namespace App\Actions\TaiKhoan;

use App\Models\TaiKhoan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DangKyTaiKhoan
{
    /**
     * Create a new class instance.
     */
    public function handle(array $data): TaiKhoan
    {
        return DB::transaction(function () use ($data) {
            $taiKhoan = TaiKhoan::create([
                'tai_khoan' => $data['tai_khoan'],
                'mat_khau' => Hash::make($data['mat_khau']),
                'trang_thai' => 'hoat_dong',
            ]);
            $taiKhoan->thongTinTaiKhoan()->create([
                'ho_ten' => $data['ho_ten'],
                'ngay_sinh' => $data['ngay_sinh'] ?? null,
                'gioi_tinh' => $data['gioi_tinh'] ?? null,
                'email' => $data['email'] ?? null,
                'so_dien_thoai' => $data['so_dien_thoai'] ?? null,
            ]);

            return $taiKhoan;
        });
    }
}
