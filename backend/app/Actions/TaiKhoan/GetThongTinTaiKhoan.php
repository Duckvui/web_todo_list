<?php

namespace App\Actions\TaiKhoan;

use App\Models\TaiKhoan;

class GetThongTinTaiKhoan
{
    public function handle(int $taiKhoanId): ?TaiKhoan
    {
        return TaiKhoan::with('thongTinTaiKhoan')
            ->find($taiKhoanId);
    }
}
