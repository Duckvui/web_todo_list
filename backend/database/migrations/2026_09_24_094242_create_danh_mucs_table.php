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
        Schema::create('danh_mucs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tai_khoan_id')
                ->constrained('tai_khoans')
                ->cascadeOnDelete();
            $table->string('ten', 100);
            $table->string('mau_sac', 7)->nullable();
            $table->timestamps();

            $table->unique(['tai_khoan_id', 'ten']);
        });

        Schema::table('viec_lams', function (Blueprint $table) {
            $table->foreignId('danh_muc_id')
                ->nullable()
                ->after('tai_khoan_id')
                ->constrained('danh_mucs')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viec_lams', function (Blueprint $table) {
            $table->dropConstrainedForeignId('danh_muc_id');
        });

        Schema::dropIfExists('danh_mucs');
    }
};
