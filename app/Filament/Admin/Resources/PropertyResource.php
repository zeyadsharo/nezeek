<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropertyResource\Pages;
use App\Filament\Admin\Resources\PropertyResource\RelationManagers;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function getNavigationLabel(): string
    {
        return __('Property.Properties');
    }

    public static function getModelLabel(): string
    {
        return __('Property.Property');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Property.Properties');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(40)
                    ->label(__('Property.Name')),

                Forms\Components\TextInput::make('arabic_title')
                    ->required()
                    ->maxLength(40)
                    ->label(__('Arabic Title')),

                Forms\Components\TextInput::make('kurdish_title')
                    ->required()
                    ->maxLength(40)
                    ->label(__('Kurdish Title')),

                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'textbox' => __('Property.Text Box'),
                        'number' => __('Property.Number'),
                        'select' => __('Property.Select'),
                        'checkbox' => __('PropertyCheckbox'),
                        'date' => __('Property.Date'),
                    ])
                    ->live()
                    ->label(__('Property.Type')),

                Forms\Components\Textarea::make('values')
                    ->columnSpanFull()
                    ->label(__('Property.Values'))
                    ->visible(fn(Forms\Get $get) => $get('type') === 'select')
                    ->helperText(__('Property.Enter values separated by comma')),

                Forms\Components\TextInput::make('unit')
                    ->maxLength(20)
                    ->label(__('Property.Unit'))
                    ->visible(fn(Forms\Get $get) => in_array($get('type'), ['number'])),

                Forms\Components\Toggle::make('is_required')
                    ->default(false)
                    ->label(__('Property.Required'))
                    ->label(__('Property.Required')),

                Forms\Components\FileUpload::make('icon')
                    ->label(__('Property.Icon'))
                    ->disk('public')
                    ->directory('properties')
                    ->image()
                    ->imageEditor()
                    ->nullable(),

                Forms\Components\TextInput::make('validation_rule')
                    ->maxLength(255)
                    ->label(__('Property.Validation Rule'))
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('Property.Name')),

                Tables\Columns\TextColumn::make('arabic_title')
                    ->searchable()
                    ->label(__('Property.Arabic Title'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('kurdish_title')
                    ->searchable()
                    ->label(__('Property.Kurdish Title'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable()

                    ->label(__('Property.Type')),

                Tables\Columns\IconColumn::make('is_required')
                    ->boolean()
                    ->label(__('Property.Required')),

                Tables\Columns\ImageColumn::make('icon')
                ->label(__('Property.Icon'))
                    ->circular(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('Property.Created At'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label(__('Property.Updated At'))
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
