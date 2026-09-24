<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chia_se_viec_lams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viec_lam_id')->constrained('viec_lams')->cascadeOnDelete();
            $table->foreignId('tai_khoan_id')->constrained('tai_khoans')->cascadeOnDelete();
            $table->string('quyen', 20)->default('xem');
            $table->string('trang_thai', 20)->default('cho_phan_hoi');
            $table->dateTime('chap_nhan_luc')->nullable();
            $table->timestamps();

            $table->unique(['viec_lam_id', 'tai_khoan_id']);
            $table->index(['tai_khoan_id', 'trang_thai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chia_se_viec_lams');
    }
};
