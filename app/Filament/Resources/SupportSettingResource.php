<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportSettingResource\Pages;
use App\Models\SupportSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportSettingResource extends Resource
{
    protected static ?string $model = SupportSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?string $navigationLabel = 'خدمة العملاء';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('id')->label('ID')->numeric()->disabled()->dehydrated(),
            Forms\Components\TextInput::make('whatsapp')->label('واتساب')->maxLength(50),
            Forms\Components\TextInput::make('email')->label('البريد')->email()->maxLength(120),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('whatsapp')->label('واتساب'),
                Tables\Columns\TextColumn::make('email')->label('البريد'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportSettings::route('/'),
            'create' => Pages\CreateSupportSetting::route('/create'),
            'edit' => Pages\EditSupportSetting::route('/{record}/edit'),
        ];
    }
}


