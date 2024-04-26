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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('model')
                    ->maxLength(255),
                Fieldset::make('Price Details')->schema([
                    TextInput::make('price')
                        ->required()
                        ->numeric(),

                    Select::make('currency')
                        ->options([
                            'USD' => 'USD',
                            'IQD' => 'IQD',
                        ])
                        ->default('IQD')


                        ->selectablePlaceholder(true),
                ]),

            
            FileUpload::make('product_image')->disk('public')
                ->directory('products')
                ->image()
                ->disk('public')
                ->imageEditor()
                ->label('Cover Image'),
                Select::make('category_id')
                    ->placeholder(__('Select Category'))
                    ->relationship(name: 'category', titleAttribute: app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title'),
                Forms\Components\TextInput::make('display_order')
                    ->required()
                    ->numeric(),

            Forms\Components\DatePicker::make('display_to')
                ->minDate(now())
                ->default(now()->addYears(1))
                ->weekStartsOnSunday(),
                
                
                Forms\Components\DatePicker::make('auto_delete_at')
                ->minDate(now())
                ->default(now()->addYears(1))
                ->weekStartsOnSunday(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                ->minLength(2)
                ->maxLength(1024),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_image')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('display_to')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('auto_delete_at')
                    ->date()
                    ->sortable(),
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
