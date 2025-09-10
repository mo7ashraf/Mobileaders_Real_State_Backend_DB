<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FavoriteResource\Pages;
use App\Models\Favorite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class FavoriteResource extends BaseResource
{
    protected static ?string $model = Favorite::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'الحسابات';
    protected static ?string $navigationLabel = 'المفضلة';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('userId')->label('المستخدم')->required()->maxLength(64),
            Forms\Components\TextInput::make('listingId')->label('الإعلان')->required()->maxLength(64),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('userId')->label('المستخدم')->searchable(),
                Tables\Columns\TextColumn::make('listingId')->label('الإعلان')->searchable(),
            ])
            ->defaultSort('userId')
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFavorites::route('/'),
        ];
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
}

