<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaResource\Pages;
use App\Filament\Resources\AreaResource\RelationManagers;
use App\Models\Area;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('arabic_title')->required(),
                TextInput::make('kurdish_title')->required(),
                Select::make('parent_id')
                    ->options(fn () => Area::pluck('arabic_title', 'id')->toArray())
                    ->label(__('Parent Area'))
                    ->nullable(),
                TextInput::make('latitude')
                    ->required()
                    ->rules('numeric'),
                TextInput::make('longitude')
                    ->required()
                    ->rules('numeric'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 Tables\Columns\TextColumn::make('arabic_title')->searchable()->sortable(),
               Tables\Columns\TextColumn::make('kurdish_title')->searchable()->sortable(),
               
                Tables\Columns\SelectColumn::make('parent_id')
                    ->options(fn () => Area::pluck('arabic_title', 'id')->toArray())
                    ->label(__('Parent Area'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('latitude')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('longitude')->searchable()->sortable(),
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
