<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Drop the default composite index so we can alter columns
            try {
                $table->dropIndex('personal_access_tokens_tokenable_type_tokenable_id_index');
            } catch (\Throwable $e) {
                // index might not exist with that name; ignore
            }

            if (Schema::hasColumn('personal_access_tokens', 'tokenable_id')) {
                // Drop and re-add as string(36) to support UUID keys without requiring doctrine/dbal
                $table->dropColumn('tokenable_id');
            }

            // Recreate tokenable_id as string UUID and re-add the composite index
            $table->string('tokenable_id', 36)->after('tokenable_type');
            $table->index(['tokenable_type', 'tokenable_id'], 'pat_tokenable_type_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            try {
                $table->dropIndex('pat_tokenable_type_id_index');
            } catch (\Throwable $e) {
                // ignore
            }

            if (Schema::hasColumn('personal_access_tokens', 'tokenable_id')) {
                $table->dropColumn('tokenable_id');
            }

            // Restore to the default big integer (if desired)
            $table->unsignedBigInteger('tokenable_id')->after('tokenable_type');
            $table->index(['tokenable_type', 'tokenable_id']);
        });
    }
};

