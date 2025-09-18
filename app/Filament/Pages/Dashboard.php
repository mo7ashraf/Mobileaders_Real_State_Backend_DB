<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\KpiStats;
use App\Filament\Widgets\ListingsByCityChart;
use App\Filament\Widgets\ListingsGeoTable;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            KpiStats::class,
            ListingsByCityChart::class,
            ListingsGeoTable::class,
        ];
    }
}
