<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category', function (Blueprint $table) {
            $table->string('slug', 64)->primary();
            $table->string('name', 191);
            $table->string('icon', 191)->nullable();
            $table->integer('sortOrder')->default(0);
            $table->boolean('enabled')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Category');
    }
};
