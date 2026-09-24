<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChiaSeViecLam extends Model
{
    use HasFactory;

    protected $fillable = [
        'viec_lam_id',
        'tai_khoan_id',
        'quyen',
        'trang_thai',
        'chap_nhan_luc',
    ];

    protected function casts(): array
    {
        return ['chap_nhan_luc' => 'datetime'];
    }

    public function viecLam(): BelongsTo
    {
        return $this->belongsTo(ViecLam::class, 'viec_lam_id');
    }

    public function taiKhoan(): BelongsTo
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_id');
    }
}
