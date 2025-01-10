<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use CodeWithDennis\FilamentSelectTree\SelectTree;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('Category.Categories');
    }
    public static function getModelLabel(): string
    {
        return __('Category.Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Category.Category');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sector_id')
                    ->relationship('sector', 'arabic_title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label(__('Category.Sector')),

                Forms\Components\TextInput::make('arabic_title')
                    ->required()
                    ->label(__('Category.Arabic Title'))
                    ->maxLength(40)
                    ->rules(['string']),

                Forms\Components\TextInput::make('kurdish_title')
                    ->required()
                    ->label(__('Category.Kurdish Title'))
                    ->maxLength(40)
                    ->rules(['string']),

                SelectTree::make('parent_id')
                    ->relationship('parent', 'arabic_title', 'parent_id')
                    ->placeholder(__('Please select a Category'))
                    ->withCount()
                    ->direction('bottom')
                    ->label(__('Category.Parent Category'))
                    ->nullable(),

                Forms\Components\TextInput::make('display_order')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label(__('Category.Display Order')),

                Forms\Components\FileUpload::make('icon')
                    ->label(__('Category.Icon'))
                    ->disk('public')
                    ->directory('categories')
                    ->image()
                    ->imageEditor()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sector.arabic_title')
                    ->label(__('Category.Sector'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('arabic_title')
                    ->label(__('Category.Arabic Title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kurdish_title')
                    ->label(__('Category.Kurdish Title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.arabic_title')
                    ->label(__('Category.Parent Category')),

                Tables\Columns\TextColumn::make('display_order')
                    ->label(__('Category.Display Order'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('icon')
                    ->label(__('Category.Icon'))
                    ->circular(),

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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
