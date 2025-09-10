<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;

abstract class BaseResource extends Resource
{
    protected static function admin(): ?\App\Models\Admin
    {
        return auth('admin')->user();
    }

    public static function canCreate(): bool
    {
        return (bool) optional(static::admin())->is_superadmin;
    }

    public static function canEdit($record): bool
    {
        return (bool) optional(static::admin())->is_superadmin;
    }

    public static function canDelete($record): bool
    {
        return (bool) optional(static::admin())->is_superadmin;
    }
}

