<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;

class ListingsGeoTable extends BaseWidget
{
    protected static ?string $heading = 'إحداثيات الإعلانات';

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Listing::query()->latest('createdAt');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')->label('العنوان')->searchable()->limit(30),
            Tables\Columns\TextColumn::make('city')->label('المدينة')->badge(),
            Tables\Columns\TextColumn::make('latitude')->label('Latitude')->sortable(),
            Tables\Columns\TextColumn::make('longitude')->label('Longitude')->sortable(),
            Tables\Columns\TextColumn::make('createdAt')->label('التاريخ')->since(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('editCoords')
                ->label('تعديل الإحداثيات')
                ->icon('heroicon-o-map-pin')
                ->form([
                    Forms\Components\TextInput::make('latitude')
                        ->label('Latitude')
                        ->numeric()
                        ->extraAttributes(['step' => 'any'])
                        ->required(),
                    Forms\Components\TextInput::make('longitude')
                        ->label('Longitude')
                        ->numeric()
                        ->extraAttributes(['step' => 'any'])
                        ->required(),
                ])
                ->action(function (Listing $record, array $data) {
                    $record->latitude = $data['latitude'];
                    $record->longitude = $data['longitude'];
                    $record->save();
                }),
        ];
    }
}
