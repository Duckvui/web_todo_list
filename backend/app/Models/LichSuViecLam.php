<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LichSuViecLam extends Model
{
    use HasFactory;

    protected $fillable = [
        'viec_lam_id',
        'nguoi_thuc_hien_id',
        'hanh_dong',
        'truong_thay_doi',
        'gia_tri_cu',
        'gia_tri_moi',
    ];

    protected function casts(): array
    {
        return [
            'gia_tri_cu' => 'array',
            'gia_tri_moi' => 'array',
        ];
    }

    public function viecLam(): BelongsTo
    {
        return $this->belongsTo(ViecLam::class, 'viec_lam_id');
    }

    public function nguoiThucHien(): BelongsTo
    {
        return $this->belongsTo(TaiKhoan::class, 'nguoi_thuc_hien_id');
    }
}
