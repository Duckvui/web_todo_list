<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViecLam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'viec_lams';

    protected $fillable = [
        'tai_khoan_id',
        'danh_muc_id',
        'tieu_de',
        'mo_ta',
        'trang_thai',
        'muc_do_hoan_thanh',
        'muc_do_uu_tien',
        'han_chot',
        'hoan_thanh_luc',
    ];

    protected function casts(): array
    {
        return [
            'muc_do_hoan_thanh' => 'integer',
            'han_chot' => 'datetime',
            'hoan_thanh_luc' => 'datetime',
        ];
    }

    public function taiKhoan(): BelongsTo
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_id');
    }

    public function nhiemVuNhos(): HasMany
    {
        return $this->hasMany(NhiemVuNho::class, 'viec_lam_id')->orderBy('thu_tu');
    }

    public function danhMuc(): BelongsTo
    {
        return $this->belongsTo(DanhMuc::class, 'danh_muc_id');
    }

    public function nhanCongViecs(): BelongsToMany
    {
        return $this->belongsToMany(NhanCongViec::class, 'nhan_viec_lam')->withTimestamps();
    }

    public function loiNhacs(): HasMany
    {
        return $this->hasMany(LoiNhac::class, 'viec_lam_id');
    }

    public function tepDinhKems(): HasMany
    {
        return $this->hasMany(TepDinhKem::class, 'viec_lam_id');
    }

    public function lichSus(): HasMany
    {
        return $this->hasMany(LichSuViecLam::class, 'viec_lam_id')->latest();
    }

    public function chiaSes(): HasMany
    {
        return $this->hasMany(ChiaSeViecLam::class, 'viec_lam_id');
    }
}
