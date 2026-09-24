<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NhiemVuNho extends Model
{
    use HasFactory;

    protected $table = 'nhiem_vu_nhos';

    protected $fillable = [
        'viec_lam_id',
        'tieu_de',
        'da_hoan_thanh',
        'thu_tu',
        'hoan_thanh_luc',
    ];

    protected function casts(): array
    {
        return [
            'da_hoan_thanh' => 'boolean',
            'thu_tu' => 'integer',
            'hoan_thanh_luc' => 'datetime',
        ];
    }

    public function viecLam(): BelongsTo
    {
        return $this->belongsTo(ViecLam::class, 'viec_lam_id');
    }
}
