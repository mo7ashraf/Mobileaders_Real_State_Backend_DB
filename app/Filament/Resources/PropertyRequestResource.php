<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyRequestResource\Pages;
use App\Models\PropertyRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class PropertyRequestResource extends Resource
{
    protected static ?string $model = PropertyRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $navigationLabel = 'طلبات عقارية';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('userId')->label('المستخدم')->required()->maxLength(64),
            Forms\Components\TextInput::make('type')->label('النوع')->maxLength(60),
            Forms\Components\TextInput::make('city')->label('المدينة')->maxLength(120),
            Forms\Components\TextInput::make('budgetMin')->label('حد أدنى')->numeric(),
            Forms\Components\TextInput::make('budgetMax')->label('حد أقصى')->numeric(),
            Forms\Components\TextInput::make('bedrooms')->label('غرف')->numeric()->minValue(0),
            Forms\Components\TextInput::make('bathrooms')->label('حمامات')->numeric()->minValue(0),
            Forms\Components\Textarea::make('notes')->label('ملاحظات')->columnSpanFull(),
            Forms\Components\Select::make('status')->label('الحالة')->options([
                'open' => 'مفتوح',
                'closed' => 'مغلق',
            ])->default('open'),
            Forms\Components\DateTimePicker::make('createdAt')->label('تاريخ الإنشاء')->seconds(false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->copyable()->searchable(),
                Tables\Columns\TextColumn::make('userId')->label('المستخدم')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('النوع')->sortable(),
                Tables\Columns\TextColumn::make('city')->label('المدينة')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge(),
                Tables\Columns\TextColumn::make('createdAt')->label('أُنشئ')->since()->sortable(),
            ])
            ->actions([
                Action::make('toOrder')
                    ->label('تحويل إلى طلب')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->requiresConfirmation()
                    ->action(function (\App\Models\PropertyRequest $record) {
                        \App\Models\Order::create([
                            'id' => (string) \Illuminate\Support\Str::uuid(),
                            'userId' => $record->userId,
                            'status' => 'open',
                            'notes' => 'Converted from request #' . $record->id,
                            'createdAt' => now(),
                        ]);
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
            'index' => Pages\ListPropertyRequests::route('/'),
            'create' => Pages\CreatePropertyRequest::route('/create'),
            'edit' => Pages\EditPropertyRequest::route('/{record}/edit'),
        ];
    }
}

