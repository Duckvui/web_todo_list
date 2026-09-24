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
        Schema::create('lich_su_viec_lams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viec_lam_id')->constrained('viec_lams')->cascadeOnDelete();
            $table->foreignId('nguoi_thuc_hien_id')
                ->nullable()
                ->constrained('tai_khoans')
                ->nullOnDelete();
            $table->string('hanh_dong', 50);
            $table->string('truong_thay_doi', 100)->nullable();
            $table->json('gia_tri_cu')->nullable();
            $table->json('gia_tri_moi')->nullable();
            $table->timestamps();

            $table->index(['viec_lam_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_viec_lams');
    }
};
