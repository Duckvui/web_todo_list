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
        Schema::create('nhan_cong_viecs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tai_khoan_id')->constrained('tai_khoans')->cascadeOnDelete();
            $table->string('ten', 50);
            $table->string('mau_sac', 7)->nullable();
            $table->timestamps();

            $table->unique(['tai_khoan_id', 'ten']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhan_cong_viecs');
    }
};
