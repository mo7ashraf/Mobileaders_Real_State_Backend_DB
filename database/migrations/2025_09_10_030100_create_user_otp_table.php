<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('UserOtp', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 32)->index();
            $table->string('code', 10);
            $table->timestamp('expiresAt');
            $table->timestamp('consumedAt')->nullable();
            $table->timestamp('createdAt')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('UserOtp');
    }
};

