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
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-c-tag';

    public static function getNavigationLabel(): string
    {
        return __('ProductCategory.ProductCategories');
    }
    public static function getModelLabel(): string
    {
        return __('ProductCategory.ProductCategory');
    }

    public static function getPluralModelLabel(): string
    {
        return __('ProductCategory.ProductCategories');
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('arabic_title')
                ->required()
                ->rules(['string'])
                ->maxLength(40)
                ->label(__('ProductCategory.Category Title(Ar)')) ,

            Forms\Components\TextInput::make('kurdish_title')
                ->required()
                ->rules(['string'])
                ->maxLength(40)
                ->label(__('ProductCategory.Category Title(Ku)')) ,
            Forms\Components\TextInput::make('display_order')
                ->required()
                ->default(0)
                ->numeric()
                ->label(__('ProductCategory.Display Order')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        Tables\Columns\TextColumn::make('arabic_title')
                        ->label(__('ProductCategory.Category Title(Ar)')),
                        Tables\Columns\TextColumn::make('kurdish_title')
                        ->label(__('ProductCategory.Category Title(Ku)')),
                    ]),
                    Tables\Columns\TextColumn::make('created_at')
                     ->label(__('ProductCategory.Created At'))
                    ->date()
                    ->icon('heroicon-m-calendar')
                ])->from('md')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver()->label(''),
                Tables\Actions\EditAction::make()->label('Edit'),
            ])
            ->paginated(false)
            ->searchable(true)
            ->defaultSort('created_at', 'desc')
            ->paginatedWhileReordering()
            ->deferLoading()
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
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
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}
