<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropertyResource\Pages;
use App\Models\Property;
use App\Models\PropertyGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Property Identifier')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Unique identifier for the property (e.g., title, price, color)'),

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

                        Forms\Components\Select::make('type')
                            ->label('Property Type')
                            ->required()
                            ->options([
                                'text' => 'Text (Single line)',
                                'textarea' => 'Textarea (Multi-line)',
                                'number' => 'Number',
                                'decimal' => 'Decimal',
                                'select' => 'Select (Single choice)',
                                'multiselect' => 'Multi-select',
                                'checkbox' => 'Checkbox',
                                'radio' => 'Radio buttons',
                                'date' => 'Date',
                                'datetime' => 'Date & Time',
                                'time' => 'Time',
                                'file' => 'File upload',
                                'image' => 'Image upload',
                                'url' => 'URL',
                                'email' => 'Email',
                                'phone' => 'Phone number',
                                'color' => 'Color picker',
                                'range' => 'Range slider',
                            ])
                            ->reactive()
                            ->helperText('Choose the input type for this property'),

                        Forms\Components\Select::make('group')
                            ->label('Property Group')
                            ->options(PropertyGroup::pluck('title', 'name'))
                            ->searchable()
                            ->preload()
                            ->helperText('Group properties together for better organization'),
                    ])->columns(2),

                Forms\Components\Section::make('Values & Options')
                    ->schema([
                        Forms\Components\KeyValue::make('options')
                            ->label('Options')
                            ->keyLabel('Value')
                            ->valueLabel('Label')
                            ->addActionLabel('Add Option')
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['select', 'multiselect', 'radio', 'checkbox']))
                            ->helperText('Add options for select, radio, or checkbox properties'),

                        Forms\Components\KeyValue::make('default_value')
                            ->label('Default Value')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->addActionLabel('Add Default')
                            ->helperText('Set default values for this property'),

                        Forms\Components\TextInput::make('unit')
                            ->label('Unit of Measurement')
                            ->maxLength(50)
                            ->placeholder('cm, kg, USD, etc.')
                            ->helperText('Unit for numeric properties'),

                        Forms\Components\TextInput::make('placeholder')
                            ->label('Placeholder Text')
                            ->maxLength(255)
                            ->placeholder('Enter Arabic placeholder text')
                            ->helperText('Hint text shown in the input field'),
                    ])->columns(2),

                Forms\Components\Section::make('Validation & Constraints')
                    ->schema([
                        Forms\Components\Toggle::make('is_required')
                            ->label('Required')
                            ->default(false)
                            ->helperText('Make this property mandatory'),

                        Forms\Components\TextInput::make('validation_rules')
                            ->label('Custom Validation Rules')
                            ->maxLength(500)
                            ->placeholder('min:0|max:100|numeric')
                            ->helperText('Laravel validation rules (optional)'),

                        Forms\Components\TextInput::make('min_length')
                            ->label('Minimum Length')
                            ->numeric()
                            ->minValue(0)
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['text', 'textarea'])),

                        Forms\Components\TextInput::make('max_length')
                            ->label('Maximum Length')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['text', 'textarea'])),

                        Forms\Components\TextInput::make('min_value')
                            ->label('Minimum Value')
                            ->numeric()
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['number', 'decimal', 'range'])),

                        Forms\Components\TextInput::make('max_value')
                            ->label('Maximum Value')
                            ->numeric()
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['number', 'decimal', 'range'])),

                        Forms\Components\TextInput::make('min_selections')
                            ->label('Minimum Selections')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'multiselect'),

                        Forms\Components\TextInput::make('max_selections')
                            ->label('Maximum Selections')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'multiselect'),
                    ])->columns(2),

                Forms\Components\Section::make('Display & Behavior')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon')
                            ->maxLength(100)
                            ->placeholder('heroicon-o-tag'),

                        Forms\Components\ColorPicker::make('color')
                            ->label('Color')
                            ->helperText('Color for UI elements'),

                        Forms\Components\TextInput::make('display_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),

                        Forms\Components\Toggle::make('is_searchable')
                            ->label('Searchable')
                            ->default(false)
                            ->helperText('Include in search results'),

                        Forms\Components\Toggle::make('is_filterable')
                            ->label('Filterable')
                            ->default(false)
                            ->helperText('Allow filtering by this property'),

                        Forms\Components\Toggle::make('is_sortable')
                            ->label('Sortable')
                            ->default(false)
                            ->helperText('Allow sorting by this property'),

                        Forms\Components\Toggle::make('is_unique')
                            ->label('Unique')
                            ->default(false)
                            ->helperText('Ensure unique values across items'),

                        Forms\Components\Toggle::make('is_encrypted')
                            ->label('Encrypted')
                            ->default(false)
                            ->helperText('Encrypt stored values (for sensitive data)'),
                    ])->columns(2),

                Forms\Components\Section::make('Advanced Features')
                    ->schema([
                        Forms\Components\KeyValue::make('conditional_logic')
                            ->label('Conditional Logic')
                            ->keyLabel('Condition')
                            ->valueLabel('Action')
                            ->addActionLabel('Add Condition')
                            ->helperText('Define when to show/hide this property'),

                        Forms\Components\Textarea::make('help_text')
                            ->label('Help Text')
                            ->maxLength(500)
                            ->placeholder('Enter Arabic help text')
                            ->rows(2)
                            ->helperText('Additional guidance for users'),
                    ])->columns(1),
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

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'text',
                        'secondary' => 'textarea',
                        'success' => 'number',
                        'warning' => 'select',
                        'danger' => 'file',
                        'info' => 'date',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('group')
                    ->label('Group')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_required')
                    ->label('Required')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_searchable')
                    ->label('Searchable')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_filterable')
                    ->label('Filterable')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('categories_count')
                    ->label('Categories')
                    ->counts('categories')
                    ->sortable(),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Filter by Type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'number' => 'Number',
                        'decimal' => 'Decimal',
                        'select' => 'Select',
                        'multiselect' => 'Multi-select',
                        'checkbox' => 'Checkbox',
                        'radio' => 'Radio',
                        'date' => 'Date',
                        'datetime' => 'Date & Time',
                        'time' => 'Time',
                        'file' => 'File',
                        'image' => 'Image',
                        'url' => 'URL',
                        'email' => 'Email',
                        'phone' => 'Phone',
                        'color' => 'Color',
                        'range' => 'Range',
                    ]),

                Tables\Filters\SelectFilter::make('group')
                    ->label('Filter by Group')
                    ->options(PropertyGroup::pluck('title', 'name')),

                Tables\Filters\TernaryFilter::make('is_required')
                    ->label('Required Status'),

                Tables\Filters\TernaryFilter::make('is_searchable')
                    ->label('Searchable Status'),

                Tables\Filters\TernaryFilter::make('is_filterable')
                    ->label('Filterable Status'),

                Tables\Filters\TernaryFilter::make('is_sortable')
                    ->label('Sortable Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('manage_categories')
                    ->label('Categories')
                    ->icon('heroicon-o-rectangle-stack')
                    ->url(fn(Property $record): string => route('filament.admin.resources.properties.edit', $record) . '?activeTab=categories'),
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'view' => Pages\ViewProperty::route('/{record}'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('categories');
    }
}
