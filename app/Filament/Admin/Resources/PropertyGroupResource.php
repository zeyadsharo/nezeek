<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropertyGroupResource\Pages;
use App\Models\PropertyGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PropertyGroupResource extends Resource
{
    protected static ?string $model = PropertyGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Group Identifier')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Unique identifier for the group (e.g., basic_info, specifications)'),

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
                            ->placeholder('heroicon-o-information-circle'),

                        Forms\Components\ColorPicker::make('color')
                            ->label('Color')
                            ->helperText('Color for UI elements'),

                        Forms\Components\Toggle::make('is_collapsible')
                            ->label('Collapsible')
                            ->default(true)
                            ->helperText('Allow users to collapse this group'),

                        Forms\Components\Toggle::make('is_expanded_by_default')
                            ->label('Expanded by Default')
                            ->default(false)
                            ->helperText('Show this group expanded initially'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Show this group to users'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Identifier')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('properties_count')
                    ->label('Properties')
                    ->counts('properties')
                    ->sortable(),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_collapsible')
                    ->label('Collapsible')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_expanded_by_default')
                    ->label('Expanded')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),

                Tables\Filters\TernaryFilter::make('is_collapsible')
                    ->label('Collapsible Status'),

                Tables\Filters\TernaryFilter::make('is_expanded_by_default')
                    ->label('Expanded by Default'),

                Tables\Filters\Filter::make('has_properties')
                    ->label('Has Properties')
                    ->query(fn(Builder $query): Builder => $query->whereHas('properties')),

                Tables\Filters\Filter::make('no_properties')
                    ->label('Empty Groups')
                    ->query(fn(Builder $query): Builder => $query->whereDoesntHave('properties')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('manage_properties')
                    ->label('Properties')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn(PropertyGroup $record): string => route('filament.admin.resources.property-groups.edit', $record) . '?activeTab=properties'),
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
            'index' => Pages\ListPropertyGroups::route('/'),
            'create' => Pages\CreatePropertyGroup::route('/create'),
            'view' => Pages\ViewPropertyGroup::route('/{record}'),
            'edit' => Pages\EditPropertyGroup::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('properties');
    }
}
