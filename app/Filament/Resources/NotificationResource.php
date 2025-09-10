<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\Notification as DomainNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class NotificationResource extends Resource
{
    protected static ?string $model = DomainNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $navigationLabel = 'الإشعارات';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('userId')->label('المستخدم')->maxLength(64),
            Forms\Components\TextInput::make('title')->label('العنوان')->required()->maxLength(180),
            Forms\Components\TextInput::make('subtitle')->label('وصف')->maxLength(255),
            Forms\Components\Toggle::make('starred')->label('مميز'),
            Forms\Components\DateTimePicker::make('readAt')->label('تمت القراءة')->seconds(false),
            Forms\Components\DateTimePicker::make('createdAt')->label('تاريخ الإنشاء')->seconds(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->copyable()->searchable(),
                Tables\Columns\TextColumn::make('userId')->label('المستخدم')->searchable(),
                Tables\Columns\TextColumn::make('title')->label('العنوان')->wrap(),
                Tables\Columns\IconColumn::make('starred')->label('مميز')->boolean(),
                Tables\Columns\TextColumn::make('readAt')->label('تمت القراءة')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('createdAt')->label('أُنشئ')->since()->sortable(),
            ])
            ->actions([
                Action::make('markRead')
                    ->label('تمييز كمقروء')
                    ->icon('heroicon-o-envelope-open')
                    ->visible(fn ($record) => empty($record->readAt))
                    ->action(function (\App\Models\Notification $record) {
                        $record->readAt = now();
                        $record->save();
                    }),
                Action::make('markUnread')
                    ->label('تمييز كغير مقروء')
                    ->icon('heroicon-o-envelope')
                    ->visible(fn ($record) => ! empty($record->readAt))
                    ->action(function (\App\Models\Notification $record) {
                        $record->readAt = null;
                        $record->save();
                    }),
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
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}

