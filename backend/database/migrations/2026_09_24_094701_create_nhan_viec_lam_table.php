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
        Schema::create('nhan_viec_lam', function (Blueprint $table) {
            $table->foreignId('viec_lam_id')->constrained('viec_lams')->cascadeOnDelete();
            $table->foreignId('nhan_cong_viec_id')->constrained('nhan_cong_viecs')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['viec_lam_id', 'nhan_cong_viec_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhan_viec_lam');
    }
};
