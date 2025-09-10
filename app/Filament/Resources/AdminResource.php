<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?string $navigationLabel = 'المشرفون';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('الاسم')->required()->maxLength(120),
            Forms\Components\TextInput::make('email')->label('البريد')->email()->required()
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('password')->label('كلمة المرور')
                ->password()
                ->revealable()
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $operation) => $operation === 'create'),
            Forms\Components\Toggle::make('is_superadmin')->label('صلاحيات كاملة (Super Admin)')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('البريد')->searchable(),
                Tables\Columns\IconColumn::make('is_superadmin')->label('Super Admin')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('أُنشئ')->since()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}

