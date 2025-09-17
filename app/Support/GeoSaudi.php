<?php

namespace App\Support;

class GeoSaudi
{
    /**
     * Return an associative array mapping normalized city keys to [latMin, latMax, lngMin, lngMax].
     */
    public static function bounds(): array
    {
        // Approximate bounding boxes (degrees) for major Saudi cities
        // Sources: public map refs; kept broad enough to safely randomize
        $boxes = [
            'riyadh' => [24.3, 25.0, 46.3, 47.1],
            'jeddah' => [21.4, 21.9, 39.0, 39.4],
            'makkah' => [21.3, 21.6, 39.7, 40.0],
            'mecca'  => [21.3, 21.6, 39.7, 40.0],
            'madinah'=> [24.3, 24.7, 39.4, 39.9],
            'medina' => [24.3, 24.7, 39.4, 39.9],
            'dammam' => [26.3, 26.6, 50.0, 50.2],
            'khobar' => [26.2, 26.35, 50.1, 50.3],
            'dhahran'=> [26.2, 26.36, 50.1, 50.3],
            'taif'   => [21.1, 21.4, 40.3, 40.6],
            'abha'   => [18.16, 18.30, 42.45, 42.70],
            'khamis mushait' => [18.2, 18.37, 42.68, 42.85],
            'jazan'  => [16.83, 16.99, 42.50, 42.70],
            'jazān'  => [16.83, 16.99, 42.50, 42.70],
            'tabuk'  => [28.32, 28.50, 36.48, 36.75],
            'hail'   => [27.44, 27.58, 41.62, 41.80],
            'buraidah' => [26.28, 26.41, 43.92, 44.10],
            'unaizah'  => [26.05, 26.14, 43.95, 44.05],
            'al kharj' => [24.05, 24.22, 47.23, 47.42],
            'al ahsa'  => [25.30, 25.43, 49.55, 49.67],
            'al hofuf' => [25.30, 25.43, 49.55, 49.67],
            'hofuf'    => [25.30, 25.43, 49.55, 49.67],
            'qatif'    => [26.50, 26.64, 49.96, 50.10],
            'jubail'   => [26.97, 27.20, 49.55, 49.77],
            'yanbu'    => [24.04, 24.12, 38.02, 38.13],
            'najran'   => [17.47, 17.61, 44.10, 44.34],
            'al baha'  => [20.00, 20.10, 41.40, 41.55],
            'arar'     => [30.90, 31.00, 41.03, 41.21],
            'sakaka'   => [29.94, 30.00, 40.19, 40.26],
            'hafar al batin' => [28.42, 28.48, 45.93, 46.10],
            'ras tanura' => [26.63, 26.69, 50.12, 50.24],
            'al ula'   => [26.55, 26.74, 37.85, 38.05],
            'al majmaah' => [25.89, 25.96, 45.30, 45.40],

            // Arabic variants
            'الرياض'  => [24.3, 25.0, 46.3, 47.1],
            'جدة'     => [21.4, 21.9, 39.0, 39.4],
            'مكة'     => [21.3, 21.6, 39.7, 40.0],
            'مكه'     => [21.3, 21.6, 39.7, 40.0],
            'المدينة المنورة' => [24.3, 24.7, 39.4, 39.9],
            'المدينة' => [24.3, 24.7, 39.4, 39.9],
            'الدمام'  => [26.3, 26.6, 50.0, 50.2],
            'الخبر'   => [26.2, 26.35, 50.1, 50.3],
            'الظهران' => [26.2, 26.36, 50.1, 50.3],
            'الطائف'  => [21.1, 21.4, 40.3, 40.6],
            'أبها'    => [18.16, 18.30, 42.45, 42.70],
            'خميس مشيط' => [18.2, 18.37, 42.68, 42.85],
            'جازان'   => [16.83, 16.99, 42.50, 42.70],
            'جيزان'   => [16.83, 16.99, 42.50, 42.70],
            'تبوك'    => [28.32, 28.50, 36.48, 36.75],
            'حائل'    => [27.44, 27.58, 41.62, 41.80],
            'بريدة'   => [26.28, 26.41, 43.92, 44.10],
            'عنيزة'   => [26.05, 26.14, 43.95, 44.05],
            'الخرج'   => [24.05, 24.22, 47.23, 47.42],
            'الأحساء'  => [25.30, 25.43, 49.55, 49.67],
            'الهفوف'   => [25.30, 25.43, 49.55, 49.67],
            'القطيف'   => [26.50, 26.64, 49.96, 50.10],
            'الجبيل'   => [26.97, 27.20, 49.55, 49.77],
            'ينبع'     => [24.04, 24.12, 38.02, 38.13],
            'نجران'    => [17.47, 17.61, 44.10, 44.34],
            'الباحة'   => [20.00, 20.10, 41.40, 41.55],
            'عرعر'     => [30.90, 31.00, 41.03, 41.21],
            'سكاكا'    => [29.94, 30.00, 40.19, 40.26],
            'حفر الباطن' => [28.42, 28.48, 45.93, 46.10],
            'رأس تنورة'  => [26.63, 26.69, 50.12, 50.24],
            'العلا'      => [26.55, 26.74, 37.85, 38.05],
            'المجمعة'    => [25.89, 25.96, 45.30, 45.40],
        ];

        return $boxes;
    }

    /**
     * Normalize a city string for lookup (lowercase, trim, collapse spaces).
     */
    public static function normalizeCity(?string $name): ?string
    {
        if (!$name) return null;
        $s = trim(mb_strtolower($name));
        // collapse multiple spaces
        $s = preg_replace('/\s+/u', ' ', $s);
        return $s;
    }

    /**
     * Get bounds for a given city name or null if unknown.
     */
    public static function getBoundsForCity(?string $name): ?array
    {
        $key = self::normalizeCity($name);
        if (!$key) return null;
        $boxes = self::bounds();
        return $boxes[$key] ?? null;
    }

    /**
     * Return a random point [lat, lng] within given bounds (latMin, latMax, lngMin, lngMax).
     */
    public static function randomPointIn(array $bounds): array
    {
        [$latMin, $latMax, $lngMin, $lngMax] = $bounds;
        $lat = $latMin + (mt_rand() / mt_getrandmax()) * ($latMax - $latMin);
        $lng = $lngMin + (mt_rand() / mt_getrandmax()) * ($lngMax - $lngMin);
        return [round($lat, 7), round($lng, 7)];
    }

    /**
     * Saudi Arabia overall bounding box (fallback).
     */
    public static function saudiBounds(): array
    {
        return [16.0, 32.5, 34.5, 55.7];
    }

    /**
     * True if lat/lng appear inside Saudi Arabia bounds.
     */
    public static function inSaudi(?float $lat, ?float $lng): bool
    {
        if ($lat === null || $lng === null) return false;
        [$latMin, $latMax, $lngMin, $lngMax] = self::saudiBounds();
        return $lat >= $latMin && $lat <= $latMax && $lng >= $lngMin && $lng <= $lngMax;
    }

    /**
     * Guess a coordinate for a city; fallback to Saudi bounds if unknown.
     */
    public static function guessForCity(?string $city): array
    {
        $b = self::getBoundsForCity($city) ?? self::saudiBounds();
        return self::randomPointIn($b);
    }
}
