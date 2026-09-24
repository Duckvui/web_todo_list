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
        if (! Schema::hasColumn('tai_khoans', 'ten_dang_nhap')) {
            return;
        }
        Schema::table('tai_khoans', function (Blueprint $table) {
            $table->renameColumn('ten_dang_nhap', 'tai_khoan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('tai_khoans', 'tai_khoan')) {
            return;
        }
        Schema::table('tai_khoans', function (Blueprint $table) {
            $table->renameColumn('tai_khoan', 'ten_dang_nhap');
        });
    }
};
