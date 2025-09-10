<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PolicyResource\Pages;
use App\Models\Policy;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Str;

class PolicyResource extends BaseResource
{
    protected static ?string $model = Policy::class;

    protected static ?string $navigationGroup    = 'الإعدادات';
    protected static ?string $navigationIcon     = 'heroicon-o-document-text';
    protected static ?string $modelLabel         = 'سياسة';
    protected static ?string $pluralModelLabel   = 'السياسات والأحكام';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('العنوان')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                    // لو كان الـ slug فارغ فقط، ولّد من العنوان
                    if (!($get('slug') ?? '')) {
                        $set('slug', Str::slug($state ?? ''));
                    }
                }),

            Forms\Components\TextInput::make('slug')
                ->label('المعرف (Slug)')
                ->helperText('أحرف لاتينية/شرطة: policy-name')
                ->rule('alpha_dash:ascii')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(191),

            // محرر Markdown
            Forms\Components\MarkdownEditor::make('contentMd')
                ->label('المحتوى (Markdown)')
                ->required()
                ->fileAttachmentsDisk('public')
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')->label('Slug')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('title')->label('العنوان')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('updatedAt')->label('آخر تحديث')->since()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف جماعي'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPolicies::route('/'),
            'create' => Pages\CreatePolicy::route('/create'),
            'view'   => Pages\ViewPolicy::route('/{record}'),
            'edit'   => Pages\EditPolicy::route('/{record}/edit'),
        ];
    }
}

