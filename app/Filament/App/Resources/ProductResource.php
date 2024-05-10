<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProductResource\Pages;
use App\Filament\App\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ToggleButtons;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-m-cube';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
            ->required()
                ->maxLength(50)
                ->label(__('Title'))
                ->placeholder(__('Enter product title')),

            Forms\Components\TextInput::make('model')
            ->maxLength(40)
                ->label(__('Model'))
                ->placeholder(__('Enter model number')),

            Forms\Components\Fieldset::make('Price Details')->schema([
                Forms\Components\TextInput::make('price')
                ->required()
                    ->numeric()
                    ->label(__('Price'))
                    ->placeholder(__('Enter price')),

                Forms\Components\ToggleButtons::make('currency')
                ->options([
                    'USD' => __('USD'),
                    'IQD' => __('IQD'),
                ])
                    ->inline()
                    ->label(__('Currency')),
            ]),

            Forms\Components\FileUpload::make('product_image')
            ->disk('public')
            ->directory('products')
            ->image()
                ->imageEditor()
                ->label(__('Product Image')),

            Forms\Components\Select::make('category_id')
            ->placeholder(__('Select Category'))
            ->relationship(name: 'category', titleAttribute: app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title')
            ->label(__('Category')),

            Forms\Components\TextInput::make('display_order')
            ->required()
                ->numeric()
                ->label(__('Display Order'))
                ->placeholder(__('Enter display order')),

            Forms\Components\DatePicker::make('display_to')
            ->minDate(now())
                ->default(now()->addYears(1))
                ->weekStartsOnSunday()
                ->label(__('Display Until')),

            Forms\Components\DatePicker::make('auto_delete_at')
            ->minDate(now())
                ->default(now()->addYears(1))
                ->weekStartsOnSunday()
                ->label(__('Auto Delete Date')),

            Forms\Components\Textarea::make('description')
            ->columnSpanFull()
                ->minLength(2)
                ->maxLength(1024)
                ->label(__('Description'))
                ->placeholder(__('Enter description')),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('product_image')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('display_to')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('auto_delete_at')
                    ->date()

                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
