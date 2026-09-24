<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_challenges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('tai_khoan_id')->constrained('tai_khoans')->cascadeOnDelete();
            $table->string('purpose', 20);
            $table->string('channel', 10);
            $table->string('destination');
            $table->string('code_hash')->nullable();
            $table->string('provider_sid')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at')->index();
            $table->timestamp('created_at');
            $table->index(['tai_khoan_id', 'purpose']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_challenges');
    }
};
