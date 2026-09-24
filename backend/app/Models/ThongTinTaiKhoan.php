<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongTinTaiKhoan extends Model
{
    protected $table = 'thong_tin_tai_khoans';

    protected $fillable = [
        'tai_khoan_id',
        'ho_ten',
        'ngay_sinh',
        'gioi_tinh',
        'email',
        'so_dien_thoai',
    ];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_id');
    }
}
