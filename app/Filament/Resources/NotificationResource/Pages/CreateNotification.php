<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateNotification extends CreateRecord
{
    protected static string $resource = NotificationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id'] = $data['id'] ?? (string) Str::uuid();
        return $data;
    }
}

