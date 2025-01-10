<?php

namespace App\Filament\Admin\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PropertiesRelationManager extends RelationManager
{
    protected static string $relationship = 'properties';
    
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'arabic_title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label(__('Property')),

                Forms\Components\TextInput::make('display_order')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label(__('Display Order')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.arabic_title')
                    ->label(__('Property Name')),
                Tables\Columns\TextColumn::make('property.type')
                    ->badge()
                    ->label(__('Type')),
                Tables\Columns\IconColumn::make('property.is_required')
                    ->boolean()
                    ->label(__('Required')),
                Tables\Columns\TextColumn::make('display_order')
                    ->sortable()
                    ->label(__('Display Order')),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}