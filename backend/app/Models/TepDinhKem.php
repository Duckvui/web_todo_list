<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TepDinhKem extends Model
{
    use HasFactory;

    protected $fillable = [
        'viec_lam_id',
        'ten_goc',
        'duong_dan',
        'o_luu_tru',
        'loai_mime',
        'kich_thuoc',
    ];

    protected function casts(): array
    {
        return ['kich_thuoc' => 'integer'];
    }

    public function viecLam(): BelongsTo
    {
        return $this->belongsTo(ViecLam::class, 'viec_lam_id');
    }
}
