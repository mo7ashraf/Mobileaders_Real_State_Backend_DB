<?php

namespace App\Filament\Resources\ListingResource\Pages;

use App\Filament\Resources\ListingResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListListings extends ListRecords
{
    protected static string $resource = ListingResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'الإعلانات';
    }
}
