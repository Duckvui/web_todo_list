<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function viecLams(): HasMany
    {
        return $this->hasMany(ViecLam::class, 'tai_khoan_id');
    }

    public function danhMucs(): HasMany
    {
        return $this->hasMany(DanhMuc::class, 'tai_khoan_id');
    }

    public function nhanCongViecs(): HasMany
    {
        return $this->hasMany(NhanCongViec::class, 'tai_khoan_id');
    }

    public function chiaSeViecLams(): HasMany
    {
        return $this->hasMany(ChiaSeViecLam::class, 'tai_khoan_id');
    }

    public function viecLamsDuocChiaSe(): BelongsToMany
    {
        return $this->belongsToMany(ViecLam::class, 'chia_se_viec_lams')
            ->withPivot(['quyen', 'trang_thai', 'chap_nhan_luc'])
            ->withTimestamps();
    }

    public function lichSuThucHiens(): HasMany
    {
        return $this->hasMany(LichSuViecLam::class, 'nguoi_thuc_hien_id');
    }
}
