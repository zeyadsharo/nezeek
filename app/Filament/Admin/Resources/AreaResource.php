<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AreaResource\Pages;
use App\Models\Area;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Title'),

                Forms\Components\TextInput::make('parent_id')
                    ->numeric()
                    ->label('Parent ID'),

                Forms\Components\Select::make('parent_id')
                    ->relationship('parentArea', 'title', 'parent_id')
                    ->searchable()
                    ->preload()
                    ->label('Parent Area'),

                Forms\Components\TextInput::make('latitude')
                    ->numeric()
                    ->label('Latitude'),

                Forms\Components\TextInput::make('longitude')
                    ->numeric()
                    ->label('Longitude'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable()
                    ->label('ID'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Title'),

                Tables\Columns\TextColumn::make('parentArea.title')
                    ->searchable()
                    ->sortable()
                    ->label('Parent Area'),

                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable()
                    ->label('Latitude'),

                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
                    ->sortable()
                    ->label('Longitude'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created At'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Updated At'),
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
            'index' => Pages\ListAreas::route('/'),
            'create' => Pages\CreateArea::route('/create'),
            'edit' => Pages\EditArea::route('/{record}/edit'),
        ];
    }
}
