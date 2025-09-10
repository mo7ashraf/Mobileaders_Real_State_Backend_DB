<?php

namespace App\Filament\Resources\SellerProfileResource\Pages;

use App\Filament\Resources\SellerProfileResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateSellerProfile extends CreateRecord
{
    protected static string $resource = SellerProfileResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id'] = $data['id'] ?? (string) Str::uuid();
        return $data;
    }
}

