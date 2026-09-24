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
        Schema::create('tep_dinh_kems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viec_lam_id')->constrained('viec_lams')->cascadeOnDelete();
            $table->string('ten_goc');
            $table->string('duong_dan');
            $table->string('o_luu_tru', 50)->default('public');
            $table->string('loai_mime', 100)->nullable();
            $table->unsignedBigInteger('kich_thuoc')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tep_dinh_kems');
    }
};
