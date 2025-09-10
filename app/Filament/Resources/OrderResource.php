<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $navigationLabel = 'الطلبات';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('userId')->label('المستخدم')->required()->maxLength(64),
            Forms\Components\Select::make('status')->label('الحالة')->options([
                'open' => 'مفتوح',
                'closed' => 'مغلق',
            ])->required(),
            Forms\Components\Textarea::make('notes')->label('ملاحظات')->columnSpanFull(),
            Forms\Components\DateTimePicker::make('createdAt')->label('تاريخ الإنشاء')->seconds(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->copyable()->searchable(),
                Tables\Columns\TextColumn::make('userId')->label('المستخدم')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'warning',
                        'closed' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('createdAt')->label('أُنشئ')->since()->sortable(),
            ])
            ->actions([
                Action::make('close')
                    ->label('إغلاق الطلب')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record) => $record->status !== 'closed')
                    ->action(function (\App\Models\Order $record) {
                        $record->status = 'closed';
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

