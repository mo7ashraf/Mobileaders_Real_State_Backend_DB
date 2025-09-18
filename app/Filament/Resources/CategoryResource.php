<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $modelLabel = 'Category';
    protected static ?string $pluralModelLabel = 'Categories';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('slug')->label('Slug')->required()->maxLength(64)
                    ->unique(ignoreRecord: true)->disabledOn('edit'),
                Forms\Components\TextInput::make('name')->label('Name')->required()->maxLength(191),
            ]),
            Forms\Components\TextInput::make('icon')->label('Icon (client key)')->maxLength(191)
                ->helperText('e.g. apartment_outlined, store_mall_directory_outlined'),
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('sortOrder')->label('Order')->numeric()->default(0),
                Forms\Components\Toggle::make('enabled')->label('Enabled')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')->label('Slug')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('icon')->label('Icon')->toggleable(isToggledHidden: true),
                Tables\Columns\TextColumn::make('sortOrder')->label('Order')->sortable(),
                Tables\Columns\IconColumn::make('enabled')->label('Enabled')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}

