<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropertyValueResource\Pages;
use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyValue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PropertyValueResource extends Resource
{
    protected static ?string $model = PropertyValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('property_id')
                            ->label('Property')
                            ->relationship('property', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('value', null)),

                        Forms\Components\TextInput::make('item_id')
                            ->label('Item ID')
                            ->required()
                            ->numeric()
                            ->helperText('ID of the item this value belongs to'),

                        Forms\Components\TextInput::make('item_type')
                            ->label('Item Type')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('App\Models\Product')
                            ->helperText('Full class name of the item model'),
                    ])->columns(2),

                Forms\Components\Section::make('Value Storage')
                    ->schema([
                        Forms\Components\TextInput::make('value')
                            ->label('Text Value')
                            ->maxLength(1000)
                            ->placeholder('Enter text value')
                            ->helperText('For simple text values'),

                        Forms\Components\KeyValue::make('json_value')
                            ->label('JSON Value')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->addActionLabel('Add Item')
                            ->helperText('For complex values (arrays, objects)'),

                        Forms\Components\TextInput::make('numeric_value')
                            ->label('Numeric Value')
                            ->numeric()
                            ->step(0.000001)
                            ->helperText('For numeric values'),

                        Forms\Components\Toggle::make('boolean_value')
                            ->label('Boolean Value')
                            ->helperText('For true/false values'),

                        Forms\Components\DatePicker::make('date_value')
                            ->label('Date Value')
                            ->helperText('For date values'),

                        Forms\Components\DateTimePicker::make('datetime_value')
                            ->label('Date & Time Value')
                            ->helperText('For date and time values'),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\TextInput::make('unit')
                            ->label('Unit')
                            ->maxLength(50)
                            ->placeholder('cm, kg, USD, etc.')
                            ->helperText('Unit of measurement'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->maxLength(1000)
                            ->rows(3)
                            ->placeholder('Additional notes about this value'),

                        Forms\Components\TextInput::make('source')
                            ->label('Source')
                            ->maxLength(255)
                            ->placeholder('Where this value came from')
                            ->helperText('Source of this value'),

                        Forms\Components\Toggle::make('is_verified')
                            ->label('Verified')
                            ->default(false)
                            ->helperText('Whether this value has been verified'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.title')
                    ->label('Category')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('property.title')
                    ->label('Property')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('item_id')
                    ->label('Item ID')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('item_type')
                    ->label('Item Type')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->copyable(),

                Tables\Columns\TextColumn::make('formatted_value')
                    ->label('Value')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit')
                    ->label('Unit')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('source')
                    ->label('Source')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'title')
                    ->label('Filter by Category'),

                Tables\Filters\SelectFilter::make('property')
                    ->relationship('property', 'title')
                    ->label('Filter by Property'),

                Tables\Filters\SelectFilter::make('item_type')
                    ->label('Filter by Item Type')
                    ->options([
                        'App\Models\Product' => 'Product',
                        'App\Models\Service' => 'Service',
                        'App\Models\Item' => 'Item',
                    ]),

                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Verification Status'),

                Tables\Filters\Filter::make('has_value')
                    ->label('Has Value')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('value')
                        ->orWhereNotNull('json_value')
                        ->orWhereNotNull('numeric_value')
                        ->orWhereNotNull('boolean_value')
                        ->orWhereNotNull('date_value')
                        ->orWhereNotNull('datetime_value')),

                Tables\Filters\Filter::make('no_value')
                    ->label('No Value')
                    ->query(fn (Builder $query): Builder => $query->whereNull('value')
                        ->whereNull('json_value')
                        ->whereNull('numeric_value')
                        ->whereNull('boolean_value')
                        ->whereNull('date_value')
                        ->whereNull('datetime_value')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (PropertyValue $record) {
                        $record->update(['is_verified' => true]);
                    })
                    ->visible(fn (PropertyValue $record): bool => !$record->is_verified),

                Tables\Actions\Action::make('unverify')
                    ->label('Unverify')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (PropertyValue $record) {
                        $record->update(['is_verified' => false]);
                    })
                    ->visible(fn (PropertyValue $record): bool => $record->is_verified),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),

                Tables\Actions\BulkAction::make('verify_selected')
                    ->label('Verify Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            $record->update(['is_verified' => true]);
                        });
                    }),

                Tables\Actions\BulkAction::make('unverify_selected')
                    ->label('Unverify Selected')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            $record->update(['is_verified' => false]);
                        });
                    }),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListPropertyValues::route('/'),
            'create' => Pages\CreatePropertyValue::route('/create'),
            'view' => Pages\ViewPropertyValue::route('/{record}'),
            'edit' => Pages\EditPropertyValue::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['category', 'property']);
    }
}
