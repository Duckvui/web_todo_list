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
        Schema::create('thong_tin_tai_khoans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tai_khoan_id')->constrained('tai_khoans')->onDelete('cascade');
            $table->string('ho_ten');
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['nam', 'nu', 'khac'])->nullable();
            $table->string('email')->unique();
            $table->string('so_dien_thoai')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_tin_tai_khoans');
    }
};
