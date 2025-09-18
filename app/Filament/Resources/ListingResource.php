<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListingResource\Pages;
use App\Models\Listing;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class ListingResource extends Resource
{
    protected static ?string $model = Listing::class;
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $modelLabel = 'إعلان';
    protected static ?string $pluralModelLabel = 'الإعلانات';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('العنوان')->required()->maxLength(255),
            Forms\Components\TextInput::make('address')->label('العنوان التفصيلي')->maxLength(255),
            Forms\Components\TextInput::make('city')->label('المدينة')->required()->maxLength(191),
            Forms\Components\TextInput::make('price')->numeric()->label('السعر')->required(),
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\TextInput::make('bedrooms')->numeric()->label('غرف')->default(0),
                Forms\Components\TextInput::make('bathrooms')->numeric()->label('حمامات')->default(0),
                Forms\Components\TextInput::make('areaSqm')->numeric()->label('المساحة م²')->default(0),
            ]),
            Forms\Components\Select::make('status')->label('الحالة')
                ->options(['rent'=>'للإيجار','sell'=>'للبيع'])->required()->default('rent'),
            Forms\Components\Select::make('category')->label('Category')->relationship('categoryModel','name')->searchable()->preload()->required()->default('apartment'),
            Forms\Components\TextInput::make('imageUrl')->label('صورة رئيسية (رابط)'),
            Forms\Components\Textarea::make('tags')->label('وسوم (JSON Array)')
                ->helperText('مثال: ["مدفوع","غير مفروش"]'),
            Forms\Components\TextInput::make('sellerId')->label('رقم البائع')->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->searchable(),
                Tables\Columns\TextColumn::make('title')->label('العنوان')->wrap()->searchable(),
                Tables\Columns\TextColumn::make('city')->label('المدينة')->badge(),
                Tables\Columns\TextColumn::make('price')->label('السعر')->money('SAR'),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge(),
                Tables\Columns\TextColumn::make('categoryModel.name')->label('النوع')->badge(),
                Tables\Columns\TextColumn::make('createdAt')->label('أُنشئ')->since(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('assignSeller')
                    ->label('تعيين لبائع')
                    ->icon('heroicon-o-user-plus')
                    ->form([
                        Forms\Components\Select::make('sellerId')
                            ->label('المستخدم (بائع)')
                            ->searchable()
                            ->options(\App\Models\User::query()->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (\App\Models\Listing $record, array $data) {
                        $record->sellerId = $data['sellerId'];
                        $record->save();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListListings::route('/'),
            'create' => Pages\CreateListing::route('/create'),
            'edit'   => Pages\EditListing::route('/{record}/edit'),
        ];
    }
}

