<?php

namespace App\Filament\Resources\PolicyResource\Pages;

use App\Filament\Resources\PolicyResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPolicy extends ViewRecord
{
    protected static string $resource = PolicyResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('تفاصيل')
                ->schema([
                    TextEntry::make('slug')->label('Slug'),
                    TextEntry::make('title')->label('العنوان'),
                    TextEntry::make('updatedAt')->label('آخر تحديث')->since(),
                ])->columns(3),

            Section::make('المحتوى')
                ->schema([
                    TextEntry::make('contentMd')->label('المحتوى')
                        ->markdown()
                        ->columnSpanFull(),
                ]),
        ]);
    }
}

