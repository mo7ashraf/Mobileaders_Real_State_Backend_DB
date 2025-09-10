<?php

namespace App\Filament\Resources\ListingResource\Pages;

use App\Filament\Resources\ListingResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditListing extends EditRecord
{
    protected static string $resource = ListingResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'تعديل إعلان';
    }

    public function getBreadcrumb(): string
    {
        return 'تعديل';
    }
}

