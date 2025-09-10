<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\KpiStats;
use App\Filament\Widgets\ListingsByCityChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            KpiStats::class,
            ListingsByCityChart::class,
        ];
    }
}
