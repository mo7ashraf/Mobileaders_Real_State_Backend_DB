<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ListingsByCityChart extends ApexChartWidget
{
    protected static ?string $heading = 'الإعلانات حسب المدينة';

    protected function getOptions(): array
    {
        $rows = DB::table('Listing')
            ->select('city', DB::raw('COUNT(*) as cnt'))
            ->groupBy('city')
            ->orderByDesc('cnt')
            ->limit(10)
            ->get();

        $labels = $rows->pluck('city')->toArray();
        $data   = $rows->pluck('cnt')->toArray();

        return [
            'chart' => ['type' => 'bar', 'height' => 320],
            'xaxis' => ['categories' => $labels],
            'series' => [[
                'name' => 'عدد الإعلانات',
                'data' => $data,
            ]],
        ];
    }
}

