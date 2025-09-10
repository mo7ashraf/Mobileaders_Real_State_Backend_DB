<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('User', function (Blueprint $table) {
            if (! Schema::hasColumn('User', 'email')) {
                $table->string('email', 191)->nullable()->unique();
            }
            if (! Schema::hasColumn('User', 'password')) {
                $table->string('password', 191)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('User', function (Blueprint $table) {
            if (Schema::hasColumn('User', 'email')) {
                $table->dropUnique(['email']);
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('User', 'password')) {
                $table->dropColumn('password');
            }
        });
    }
};

