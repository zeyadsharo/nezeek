<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SectorResource\Pages;
use App\Filament\Admin\Resources\SectorResource\RelationManagers;
use App\Models\Sector;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectorResource extends Resource
{
    protected static ?string $model = Sector::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Title'),

                Forms\Components\Textarea::make('description')
                    ->maxLength(1000)
                    ->label('Description')
                    ->rows(3),

                Forms\Components\TextInput::make('display_order')
                    ->numeric()
                    ->default(0)
                    ->label('Display Order'),

                Forms\Components\TextInput::make('display_state')
                    ->numeric()
                    ->default(1)
                    ->label('Display State'),

                Forms\Components\TextInput::make('icon')
                    ->maxLength(255)
                    ->label('Icon'),

                Forms\Components\TextInput::make('activation_state')
                    ->numeric()
                    ->default(1)
                    ->label('Activation State'),
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

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->label('Description'),

                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->sortable()
                    ->label('Display Order'),

                Tables\Columns\TextColumn::make('display_state')
                    ->numeric()
                    ->sortable()
                    ->label('Display State'),

                Tables\Columns\TextColumn::make('icon')
                    ->searchable()
                    ->label('Icon'),

                Tables\Columns\TextColumn::make('activation_state')
                    ->numeric()
                    ->sortable()
                    ->label('Activation State'),

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
            'index' => Pages\ListSectors::route('/'),
            'create' => Pages\CreateSector::route('/create'),
            'edit' => Pages\EditSector::route('/{record}/edit'),
        ];
    }
}
