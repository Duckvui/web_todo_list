<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoiNhac extends Model
{
    use HasFactory;

    protected $fillable = [
        'viec_lam_id',
        'thoi_gian_nhac',
        'phuong_thuc',
        'trang_thai',
        'noi_dung',
        'da_gui_luc',
    ];

    protected function casts(): array
    {
        return [
            'thoi_gian_nhac' => 'datetime',
            'da_gui_luc' => 'datetime',
        ];
    }

    public function viecLam(): BelongsTo
    {
        return $this->belongsTo(ViecLam::class, 'viec_lam_id');
    }
}
