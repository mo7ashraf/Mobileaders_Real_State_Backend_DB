<?php

namespace App\Filament\Resources\ListingResource\Pages;

use App\Filament\Resources\ListingResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class CreateListing extends CreateRecord
{
    protected static string $resource = ListingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id'] = $data['id'] ?? (string) Str::uuid();
        return $data;
    }

    public function getTitle(): string | Htmlable
    {
        return 'إضافة إعلان';
    }

    public function getBreadcrumb(): string
    {
        return 'إضافة';
    }
}

