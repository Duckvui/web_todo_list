<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TaiKhoan extends Authenticatable
{
    use HasFactory;

    protected $table = 'tai_khoans';

    protected $authPasswordName = 'mat_khau';

    protected $fillable = ['tai_khoan', 'mat_khau', 'trang_thai'];

    protected $hidden = ['mat_khau', 'remember_token', 'auth_version'];

    protected function casts(): array
    {
        return ['auth_version' => 'integer', 'email_verified_at' => 'datetime', 'phone_verified_at' => 'datetime'];
    }

    public function thongTinTaiKhoan(): HasOne
    {
        return $this->hasOne(ThongTinTaiKhoan::class, 'tai_khoan_id');
    }
}
