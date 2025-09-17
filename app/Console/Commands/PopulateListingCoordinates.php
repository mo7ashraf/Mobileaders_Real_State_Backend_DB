<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;
use App\Support\GeoSaudi;

class PopulateListingCoordinates extends Command
{
    protected $signature = 'listings:geo-populate {--overwrite : Overwrite existing coordinates} {--dry-run : Do not save changes}';
    protected $description = 'Populate latitude/longitude for listings within their Saudi city bounds';

    public function handle(): int
    {
        $overwrite = (bool) $this->option('overwrite');
        $dry = (bool) $this->option('dry-run');

        $q = Listing::query();
        if (!$overwrite) {
            $q->whereNull('latitude')->orWhereNull('longitude');
        }

        $rows = $q->get();
        $this->info('Processing ' . $rows->count() . ' listings' . ($dry ? ' (dry-run)' : ''));

        $updated = 0; $fallback = 0; $byCity = []; $fallbackByName = [];
        foreach ($rows as $l) {
            $city = $l->city;
            $bounds = GeoSaudi::getBoundsForCity($city);

            // If not overwriting and both filled and valid, skip
            if (!$overwrite && $l->latitude !== null && $l->longitude !== null && GeoSaudi::inSaudi((float)$l->latitude, (float)$l->longitude)) {
                continue;
            }

            if (!$bounds) {
                $fallback++;
                $key = GeoSaudi::normalizeCity($city) ?: 'null';
                $fallbackByName[$key] = ($fallbackByName[$key] ?? 0) + 1;
                $bounds = GeoSaudi::saudiBounds();
            } else {
                $key = GeoSaudi::normalizeCity($city) ?: 'unknown';
                $byCity[$key] = ($byCity[$key] ?? 0) + 1;
            }

            [$lat, $lng] = GeoSaudi::randomPointIn($bounds);

            if ($dry) {
                $this->line("- {$l->id} city='{$city}' -> {$lat},{$lng} ");
                $updated++;
                continue;
            }

            $l->latitude = $lat;
            $l->longitude = $lng;
            $l->save();
            $updated++;
        }

        $this->info("Updated: {$updated}; Fallback (KSA-wide): {$fallback}");
        if (!empty($byCity)) {
            $this->info('Per-city counts:');
            foreach ($byCity as $c => $cnt) $this->line("  - {$c}: {$cnt}");
        }
        if (!empty($fallbackByName)) {
            $this->warn('Fallback cities (not matched, used KSA-wide bounds):');
            foreach ($fallbackByName as $c => $cnt) $this->line("  - {$c}: {$cnt}");
        }

        return self::SUCCESS;
    }
}
