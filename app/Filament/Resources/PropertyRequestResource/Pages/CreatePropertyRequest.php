<?php

namespace App\Filament\Resources\PropertyRequestResource\Pages;

use App\Filament\Resources\PropertyRequestResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePropertyRequest extends CreateRecord
{
    protected static string $resource = PropertyRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id'] = $data['id'] ?? (string) Str::uuid();
        return $data;
    }
}

