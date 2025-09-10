<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'الحسابات';
    protected static ?string $navigationLabel = 'المستخدمون';
    protected static ?string $modelLabel = 'مستخدم';
    protected static ?string $pluralModelLabel = 'المستخدمون';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('phone')->label('الجوال')->maxLength(191)->required(),
            Forms\Components\TextInput::make('name')->label('الاسم')->maxLength(255)->required(),
            Forms\Components\TextInput::make('avatarUrl')->label('صورة (رابط)')->maxLength(255),
            Forms\Components\DateTimePicker::make('createdAt')->label('تاريخ الإنشاء')->seconds(false),
            Forms\Components\Textarea::make('bio')->label('نبذة')->columnSpanFull(),
            Forms\Components\TextInput::make('orgName')->label('المنشأة')->maxLength(120),
            Forms\Components\TextInput::make('accRole')->label('الصفة')->maxLength(60),
            Forms\Components\Textarea::make('channels')->label('قنوات (JSON)')->columnSpanFull(),
            Forms\Components\Textarea::make('socialLinks')->label('روابط التواصل (JSON)')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('الجوال')->searchable(),
                Tables\Columns\TextColumn::make('accRole')->label('الصفة')->badge()->sortable(),
                Tables\Columns\TextColumn::make('createdAt')->label('أُنشئ')->since()->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}


