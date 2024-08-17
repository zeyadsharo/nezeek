<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProductResource\Pages;
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
                    // Left: Product Image
                    Tables\Columns\ImageColumn::make('product_image')
                    ->label(__('Product.Image'))
                    ->circular(false) // Keep the image rectangular as in the example
                        ->grow(false), // Prevent image from growing to occupy too much space

                    // Right: Details in a Stack
                    Stack::make([
                        // Product Title
                        Tables\Columns\TextColumn::make('title')
                        ->label(__('Product.Title'))
                        ->icon('heroicon-s-shopping-bag')
                        ->searchable()
                            ->weight('bold'), // Make the title stand out

                        // Product Category
                        Tables\Columns\TextColumn::make('category.arabic_title')
                        ->label(__('Product.Category'))
                        ->icon('heroicon-s-tag')
                        ->searchable(),

                        // Price and Currency
                        Tables\Columns\TextColumn::make('price')
                        ->numeric()
                            ->label(__('Product.Price'))
                            ->money(
                                currency: function ($column, Product $record) {
                                    return $record->currency === 'USD' ? 'USD' : 'IQD';
                                },
                                locale: function ($column, Product $record) {
                                    return $record->currency === 'USD' ? 'en_US' : 'ar_IQ';
                                }
                            )
                            ->size('xl') // Make the price more prominent
                            ->extraAttributes(['class' => 'text-red-600']), // Style the price in red

                        // Created At
                        Tables\Columns\TextColumn::make('created_at')
                        ->label(__('Product.Created At'))
                        ->dateTime('Y-m-d H:i') // Format the datetime
                        ->icon('heroicon-s-calendar')
                        ->extraAttributes(['class' => 'text-gray-500 text-sm']), // Smaller, less prominent text
                    ]),
                ])->from('md') // Stack the layout starting from medium screens and above
            ])
            ->filters([
                // Add any filters here if needed
            ])
            ->paginated(false)
            ->searchable(true)
            ->defaultSort('created_at', 'desc')
            ->paginatedWhileReordering()
            ->deferLoading()
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\ViewAction::make()->label('')->slideOver(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null);
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


