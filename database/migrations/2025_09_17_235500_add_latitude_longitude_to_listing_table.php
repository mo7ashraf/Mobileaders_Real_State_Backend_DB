<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table name is singular and capitalized in this project: 'Listing'
        Schema::table('listing', function (Blueprint $table) {
            // Use sufficient precision for GPS coordinates. Make them nullable.
            if (!Schema::hasColumn('Listing', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('address');
            }
            if (!Schema::hasColumn('Listing', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('listing', function (Blueprint $table) {
            if (Schema::hasColumn('Listing', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('Listing', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }
};
