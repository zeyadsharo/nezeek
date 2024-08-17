<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProductResource\Pages;
use App\Filament\App\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-m-cube';
    public static function getNavigationLabel(): string
    {
        return __('Product.Products');
    }
    public static function getModelLabel(): string
    {
        return __('Product.Product');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Product.Products');
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
            ->required()
                ->maxLength(50)
                ->label(__('Product.title'))
                ->placeholder( __('Product.Laptop, Mobile, etc')),

            Forms\Components\TextInput::make('model')
            ->maxLength(40)
                ->label(__('Product.model'))
                ->placeholder(__('Product.Hp, Samsung, etc')),

            Forms\Components\Fieldset::make('Price Details')
             ->label(__('Product.Price Details'))
            ->schema([
                Forms\Components\TextInput::make('price')
                ->required()
                    ->numeric()
                    ->label(__('Product.Price'))
                    ->placeholder('600, 1000, etc'),

                Forms\Components\ToggleButtons::make('currency')
                ->options([
                    'USD' => __('Product.USD'),
                    'IQD' => __('Product.IQD'),
                ])
                    ->inline()
                    ->label(__('Product.Currency')),
            ]),

            Forms\Components\FileUpload::make('product_image')
            ->disk('public')
            ->directory('products')
            ->image()
                ->imageEditor()
                ->label(__('Product.Image')),

            Forms\Components\Select::make('category_id')
            ->placeholder(__('Product.Select Category'))
            ->relationship(name: 'category', titleAttribute: app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title')
            ->label(__('Product.Category')),

            Forms\Components\TextInput::make('display_order')
            ->required()
                ->numeric()
                ->label(__('Product.Display Order'))
                ->placeholder('1, 2, 3, etc'),
            Forms\Components\DatePicker::make('display_to')
            ->minDate(now())
                ->default(now()->addYears(1))
                ->weekStartsOnSunday()
                ->label(__('Product.Display To')),

            Forms\Components\DatePicker::make('auto_delete_at')
            ->minDate(now())
                ->default(now()->addYears(1))
                ->weekStartsOnSunday()
                ->label(__('Product.Auto Delete At')),

            Forms\Components\Textarea::make('description')
            ->columnSpanFull()
                ->minLength(2)
                ->maxLength(1024)
                ->label(__('Product.Description'))
                ->placeholder(__('Product.Description about the product')),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Tables\Columns\ImageColumn::make('product_image')
                    ->label(__('Product.Image'))
                    ->searchable(),
                    Stack::make([
                        Tables\Columns\TextColumn::make('title')
                        ->label(__('Product.Title'))
                        ->searchable(),
                        Tables\Columns\TextColumn::make('model')
                        ->label(__('Product.Model'))
                        ->searchable(),
                    Tables\Columns\TextColumn::make('category.arabic_title')
                        ->label(__('Product.Category'))
                        ->searchable(),
                    ]),
                    Stack::make([
                        Tables\Columns\TextColumn::make('price')
                        ->numeric()
                       ->label(__('Product.Price'))
                        ->money(currency: 'IQD')
                        ->sortable(),
                        // Tables\Columns\TextColumn::make('currency')
                        // ->label(__('Product.Currency'))
                        // ->searchable(),
                    ]),
                    // Stack::make([
                    //     Tables\Columns\TextColumn::make('display_to')
                    //     ->label(__('Product.Display To'))
                    //     ->date()
                    //         ->toggleable(isToggledHiddenByDefault: true),
                    //     Tables\Columns\TextColumn::make('auto_delete_at')
                    //     ->label(__('Product.Auto Delete At'))
                    //     ->date()
                    //         ->toggleable(isToggledHiddenByDefault: true),
                    // ]),
                    Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Product.Created At'))
                    ->dateTime()
                    ->icon('heroicon-s-calendar')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])->from('md')
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


