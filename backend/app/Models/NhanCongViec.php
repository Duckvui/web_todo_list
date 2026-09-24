<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NhanCongViec extends Model
{
    use HasFactory;

    protected $fillable = ['tai_khoan_id', 'ten', 'mau_sac'];

    public function taiKhoan(): BelongsTo
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_id');
    }

    public function viecLams(): BelongsToMany
    {
        return $this->belongsToMany(ViecLam::class, 'nhan_viec_lam')->withTimestamps();
    }
}
