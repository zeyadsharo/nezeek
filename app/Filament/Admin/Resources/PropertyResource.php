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

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(40)
                    ->label(__('Name')),

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
                        'textbox' => __('Text Box'),
                        'number' => __('Number'),
                        'select' => __('Select'),
                        'checkbox' => __('Checkbox'),
                        'date' => __('Date'),
                    ])
                    ->live(),

                Forms\Components\Textarea::make('values')
                    ->columnSpanFull()
                    ->visible(fn(Forms\Get $get) => $get('type') === 'select')
                    ->helperText(__('Enter values separated by comma')),

                Forms\Components\TextInput::make('unit')
                    ->maxLength(20)
                    ->visible(fn(Forms\Get $get) => in_array($get('type'), ['number'])),

                Forms\Components\Toggle::make('is_required')
                    ->default(false)
                    ->label(__('Required')),

                Forms\Components\FileUpload::make('icon')
                    ->label(__('Icon'))
                    ->disk('public')
                    ->directory('properties')
                    ->image()
                    ->imageEditor()
                    ->nullable(),

                Forms\Components\TextInput::make('validation_rule')
                    ->maxLength(255)
                    ->label(__('Validation Rule'))
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('arabic_title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kurdish_title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_required')
                    ->boolean(),

                Tables\Columns\ImageColumn::make('icon')
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
