<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('tai_khoans', 'created_at')) {
            Schema::table('tai_khoans', fn (Blueprint $table) => $table->timestamps());
        }
        Schema::table('tai_khoans', function (Blueprint $table) {
            $table->rememberToken();
            $table->unsignedInteger('auth_version')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
        });
        Schema::table('thong_tin_tai_khoans', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tai_khoans', function (Blueprint $table) {
            $table->dropColumn(['remember_token', 'auth_version', 'email_verified_at', 'phone_verified_at']);
        });
        // Keep email nullable to preserve phone-only accounts during rollback.
    }
};
