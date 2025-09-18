<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Normalize names (case) & engines; align columns for FK
        try { DB::statement('RENAME TABLE `Listing` TO `listing`'); } catch (\Throwable $e) {}
        try { DB::statement('RENAME TABLE `Category` TO `category`'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE `listing` ENGINE=InnoDB'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE `category` ENGINE=InnoDB'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE `category` MODIFY `slug` VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE `listing` MODIFY `category` VARCHAR(191) COLLATE utf8mb4_unicode_ci NULL'); } catch (\Throwable $e) {}

        Schema::table('listing', function (Blueprint $table) {
            // Ensure we have an index, then add FK to Category.slug
            if (!self::hasIndex('listing', 'listing_category_idx')) {
                $table->index('category', 'listing_category_idx');
            }
            try {
                $table->foreign('category', 'listing_category_fk')
                    ->references('slug')->on('category')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
            } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        Schema::table('listing', function (Blueprint $table) {
            try { $table->dropForeign('listing_category_fk'); } catch (\Throwable $e) {}
            try { $table->dropIndex('listing_category_idx'); } catch (\Throwable $e) {}
        });
    }

    private static function hasIndex(string $table, string $index): bool
    {
        // Portable check without DBAL: ask the connection for indexes via SHOW INDEX
        try {
            $conn = Schema::getConnection();
            $name = $conn->getTablePrefix() . $table;
            $rows = $conn->select("SHOW INDEX FROM `{$name}` WHERE Key_name = ?", [$index]);
            return !empty($rows);
        } catch (\Throwable $e) {
            return false;
        }
    }
};
