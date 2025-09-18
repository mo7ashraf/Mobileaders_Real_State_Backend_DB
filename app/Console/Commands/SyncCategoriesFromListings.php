<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class SyncCategoriesFromListings extends Command
{
    protected $signature = 'categories:sync-from-listings {--dry-run}';
    protected $description = 'Create missing categories from distinct Listing.category values and align slugs';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        $distinct = DB::table('listing')->select('category')->distinct()->pluck('category')->filter()->values();
        if ($distinct->isEmpty()) {
            $this->info('No categories found in Listing.');
            return self::SUCCESS;
        }

        $mapName = function(string $slug): string {
            $slug = trim(mb_strtolower($slug));
            $names = [
                'apartment'=>'شقق', 'villa'=>'فلل', 'office'=>'مكاتب', 'resthouse'=>'استراحات',
                'land'=>'أراضي', 'shop'=>'محلات',
            ];
            return $names[$slug] ?? Str::title(str_replace('-', ' ', $slug));
        };

        $mapIcon = function(string $slug): ?string {
            $icons = [
                'apartment'=>'apartment_outlined',
                'villa'=>'house_outlined',
                'office'=>'corporate_fare_outlined',
                'resthouse'=>'hotel_outlined',
                'land'=>'villa_outlined',
                'shop'=>'store_mall_directory_outlined',
            ];
            return $icons[$slug] ?? null;
        };

        $created = 0; $skipped = 0;
        foreach ($distinct as $slug) {
            $norm = trim(mb_strtolower((string)$slug));
            if ($norm === '') { $skipped++; continue; }
            $exists = Category::where('slug', $norm)->exists();
            if ($exists) { $skipped++; continue; }

            $payload = [
                'slug' => $norm,
                'name' => $mapName($norm),
                'icon' => $mapIcon($norm),
                'sortOrder' => 0,
                'enabled' => true,
            ];

            if ($dry) {
                $this->line('Would create: '.json_encode($payload, JSON_UNESCAPED_UNICODE));
                $created++;
                continue;
            }
            Category::create($payload);
            $created++;
        }

        $this->info("Created {$created}, skipped {$skipped}");
        return self::SUCCESS;
    }
}
