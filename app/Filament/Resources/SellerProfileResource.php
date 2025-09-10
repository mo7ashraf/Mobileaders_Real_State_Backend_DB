<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerProfileResource\Pages;
use App\Models\SellerProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SellerProfileResource extends Resource
{
    protected static ?string $model = SellerProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'الحسابات';
    protected static ?string $navigationLabel = 'ملفات البائعين';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('userId')->label('المستخدم')->required()->maxLength(64),
            Forms\Components\Toggle::make('verified')->label('موثّق')->required(),
            Forms\Components\TextInput::make('clients')->label('عملاء')->numeric()->minValue(0),
            Forms\Components\TextInput::make('rating')->label('تقييم')->numeric()->step('0.1')->minValue(0)->maxValue(5),
            Forms\Components\Textarea::make('badges')->label('شارات (JSON)')->rows(2),
            Forms\Components\TextInput::make('joinedHijri')->label('سنة هجرية')->maxLength(50),
            Forms\Components\TextInput::make('joinedText')->label('تاريخ الانضمام')->maxLength(50),
            Forms\Components\TextInput::make('regionText')->label('النطاق')->maxLength(80),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->copyable()->searchable(),
                Tables\Columns\TextColumn::make('userId')->label('المستخدم')->searchable(),
                Tables\Columns\IconColumn::make('verified')->label('موثّق')->boolean(),
                Tables\Columns\TextColumn::make('clients')->label('عملاء')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('rating')->label('تقييم')->numeric(1)->sortable(),
                Tables\Columns\TextColumn::make('regionText')->label('النطاق'),
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
            'index' => Pages\ListSellerProfiles::route('/'),
            'create' => Pages\CreateSellerProfile::route('/create'),
            'edit' => Pages\EditSellerProfile::route('/{record}/edit'),
        ];
    }
}


