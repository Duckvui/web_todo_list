<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('viec_lams', function (Blueprint $table) {
            $table->foreignId('tai_khoan_id')
                ->after('id')
                ->constrained('tai_khoans')
                ->cascadeOnDelete();
            $table->string('tieu_de');
            $table->text('mo_ta')->nullable();
            $table->string('trang_thai', 20)->default('chua_lam')->index();
            $table->string('muc_do_uu_tien', 20)->default('trung_binh')->index();
            $table->dateTime('han_chot')->nullable()->index();
            $table->dateTime('hoan_thanh_luc')->nullable();
            $table->softDeletes();

            $table->index(['tai_khoan_id', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::table('viec_lams', function (Blueprint $table) {
            $table->dropForeign(['tai_khoan_id']);
            $table->dropIndex(['tai_khoan_id', 'trang_thai']);
            $table->dropIndex(['trang_thai']);
            $table->dropIndex(['muc_do_uu_tien']);
            $table->dropIndex(['han_chot']);
            $table->dropColumn([
                'tai_khoan_id',
                'tieu_de',
                'mo_ta',
                'trang_thai',
                'muc_do_uu_tien',
                'han_chot',
                'hoan_thanh_luc',
                'deleted_at',
            ]);
        });
    }
};
