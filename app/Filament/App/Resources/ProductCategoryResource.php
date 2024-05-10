<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProductCategoryResource\Pages;
use App\Filament\App\Resources\ProductCategoryResource\RelationManagers;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-c-tag';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('arabic_title')
            ->required()
                ->rules(['string'])
                ->maxLength(40)
                ->label(__('Arabic Title'))  // Translated label
                ->placeholder(__('Enter Arabic Title')),  // Translated placeholder

            Forms\Components\TextInput::make('kurdish_title')
            ->required()
                ->rules(['string'])
                ->maxLength(40)
                ->label(__('Kurdish Title'))  // Translated label
                ->placeholder(__('Enter Kurdish Title')),  // Translated placeholder

            Forms\Components\TextInput::make('display_order')
            ->required()
                ->default(0)
                ->numeric()
                ->label(__('Display Order'))  // Translated label
                ->placeholder(__('Enter Display Order'))  // Translated placeholder
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('arabic_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kurdish_title')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('customer_id')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}
