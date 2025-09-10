<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppSettingResource\Pages;
use App\Models\AppSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AppSettingResource extends Resource
{
    protected static ?string $model = AppSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?string $navigationLabel = 'إعدادات التطبيق';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('id')->label('ID')->numeric()->disabled()->dehydrated(),
            Forms\Components\TextInput::make('language')->label('اللغة')->default('ar'),
            Forms\Components\TextInput::make('theme')->label('المظهر')->default('system'),
            Forms\Components\Textarea::make('notifications')->label('إشعارات (JSON)'),
            Forms\Components\Textarea::make('privacy')->label('خصوصية (JSON)'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('language')->label('اللغة'),
                Tables\Columns\TextColumn::make('theme')->label('المظهر'),
                Tables\Columns\TextColumn::make('notifications')->label('إشعارات'),
                Tables\Columns\TextColumn::make('privacy')->label('خصوصية'),
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
            'index' => Pages\ListAppSettings::route('/'),
            'create' => Pages\CreateAppSetting::route('/create'),
            'edit' => Pages\EditAppSetting::route('/{record}/edit'),
        ];
    }
}


