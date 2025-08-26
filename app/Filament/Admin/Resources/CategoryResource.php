<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Models\Sector;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Slug/Identifier')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Unique identifier for the category (e.g., electronics, phones)'),

                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter Arabic title'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->maxLength(1000)
                            ->placeholder('Enter Arabic description')
                            ->rows(3),

                        Forms\Components\Select::make('sector_id')
                            ->label('Sector')
                            ->relationship('sector', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Category')
                            ->relationship('parent', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select parent category (optional)'),
                    ])->columns(2),

                Forms\Components\Section::make('Display & Organization')
                    ->schema([
                        Forms\Components\TextInput::make('display_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),

                        Forms\Components\TextInput::make('icon')
                            ->label('Icon')
                            ->maxLength(100)
                            ->placeholder('heroicon-o-device-phone-mobile'),

                        Forms\Components\FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->directory('categories')
                            ->maxSize(2048),

                        Forms\Components\ColorPicker::make('color')
                            ->label('Color')
                            ->helperText('Color for UI elements'),
                    ])->columns(2),

                Forms\Components\Section::make('Status & SEO')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Show this category to users'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false)
                            ->helperText('Highlight this category'),

                        Forms\Components\TextInput::make('slug')
                            ->label('SEO Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('URL-friendly version of the title'),

                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255)
                            ->helperText('SEO meta title'),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(500)
                            ->rows(2)
                            ->helperText('SEO meta description'),

                        Forms\Components\TextInput::make('meta_keywords')
                            ->label('Meta Keywords')
                            ->maxLength(255)
                            ->helperText('SEO meta keywords (comma separated)'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('sector.title')
                    ->label('Sector')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.title')
                    ->label('Parent')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('children_count')
                    ->label('Subcategories')
                    ->counts('children')
                    ->sortable(),

                Tables\Columns\TextColumn::make('properties_count')
                    ->label('Properties')
                    ->counts('properties')
                    ->sortable(),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sector')
                    ->relationship('sector', 'title')
                    ->label('Filter by Sector'),

                Tables\Filters\SelectFilter::make('parent')
                    ->relationship('parent', 'title')
                    ->label('Filter by Parent Category')
                    ->placeholder('All Categories'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status'),

                Tables\Filters\Filter::make('has_children')
                    ->label('Has Subcategories')
                    ->query(fn(Builder $query): Builder => $query->whereHas('children')),

                Tables\Filters\Filter::make('no_parent')
                    ->label('Root Categories')
                    ->query(fn(Builder $query): Builder => $query->whereNull('parent_id')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('manage_properties')
                    ->label('Properties')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn(Category $record): string => route('filament.admin.resources.categories.edit', $record) . '?activeTab=properties'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order');
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
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount(['children', 'properties']);
    }
}
