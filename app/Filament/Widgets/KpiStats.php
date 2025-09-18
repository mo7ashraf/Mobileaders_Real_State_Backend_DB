<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use App\Models\Order;
use App\Models\PropertyRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class KpiStats extends BaseWidget
{
    protected function getStats(): array
    {
        $listingsAll = Listing::count();
        $listings7d  = Listing::where('createdAt', '>=', now()->subDays(7))->count();
        $ordersOpen  = (Schema::hasTable('order') ? Order::where('status','open')->count() : 0);
        $requestsOpen = (Schema::hasTable('propertyrequest') ? PropertyRequest::where('status','open')->count() : 0);

        return [
            Stat::make('كل الإعلانات', number_format($listingsAll))
                ->description("آخر 7 أيام: {$listings7d}")
                ->color('success'),
            Stat::make('طلبات مفتوحة', number_format($ordersOpen))
                ->icon('heroicon-o-clipboard-document-list'),
            Stat::make('طلبات عقارية قيد المتابعة', number_format($requestsOpen))
                ->icon('heroicon-o-inbox'),
        ];
    }
}






