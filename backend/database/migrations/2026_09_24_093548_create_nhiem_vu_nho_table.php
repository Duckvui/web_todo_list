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
        Schema::table('viec_lams', function (Blueprint $table) {
            $table->unsignedTinyInteger('muc_do_hoan_thanh')->default(0)->after('trang_thai');
        });

        Schema::create('nhiem_vu_nhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viec_lam_id')
                ->constrained('viec_lams')
                ->cascadeOnDelete();
            $table->string('tieu_de');
            $table->boolean('da_hoan_thanh')->default(false)->index();
            $table->unsignedInteger('thu_tu')->default(0);
            $table->dateTime('hoan_thanh_luc')->nullable();
            $table->timestamps();

            $table->index(['viec_lam_id', 'thu_tu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhiem_vu_nhos');

        Schema::table('viec_lams', function (Blueprint $table) {
            $table->dropColumn('muc_do_hoan_thanh');
        });
    }
};
