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
        Schema::create('loi_nhacs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viec_lam_id')->constrained('viec_lams')->cascadeOnDelete();
            $table->dateTime('thoi_gian_nhac')->index();
            $table->string('phuong_thuc', 20)->default('trong_ung_dung');
            $table->string('trang_thai', 20)->default('cho_gui')->index();
            $table->text('noi_dung')->nullable();
            $table->dateTime('da_gui_luc')->nullable();
            $table->timestamps();

            $table->index(['viec_lam_id', 'trang_thai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loi_nhacs');
    }
};
