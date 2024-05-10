<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AreaResource\Pages\EditArea;
use App\Filament\Admin\Resources\AreaResource\Pages\ListAreas;
use App\Models\Area;
use CodeWithDennis\FilamentSelectTree\SelectTree;
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

    protected static ?string $navigationIcon = 'heroicon-c-cube';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([TextInput::make('arabic_title')
                ->required()
                ->label(__('Arabic Title'))
                ->rules(['string', 'max:40']),

            TextInput::make('kurdish_title')
                ->required()
                ->label(__('Kurdish Title'))
                ->rules(['string', 'max:40']),

            SelectTree::make('parent_id')
                ->relationship('parentArea', 'arabic_title', 'parent_id')
                ->placeholder(__('Please select a Area'))
                ->withCount()
                ->direction('bottom') // Corrected typo from 'buttom' to 'bottom'
                ->label(__('Parent Area'))
                ->nullable(),

            TextInput::make('latitude')
                ->required()
                ->label(__('Latitude'))
                ->rules(['numeric', 'regex:/^-?\d{1,4}\.\d{1,9}$/']),

            TextInput::make('longitude')
                ->required()
                ->label(__('Longitude'))
                ->rules(['numeric', 'regex:/^-?\d{1,3}\.\d{1,9}$/']),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 Tables\Columns\TextColumn::make('arabic_title')->searchable()->sortable(),
               Tables\Columns\TextColumn::make('kurdish_title')->searchable()->sortable(),
               
                Tables\Columns\TextColumn::make('parentArea.arabic_title')
                    ->label(__('Parent Area')),
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
            'index' => ListAreas::route('/'),
            'create' => \App\Filament\Admin\Resources\AreaResource\Pages\CreateArea::route('/create'),
            'edit' => EditArea::route('/{record}/edit'),
        ];
    }
  public static function getLabel(): string
    {
        return __('Area.ModelLabel');
    }
    //PluralModelLabel
    public static function getPluralLabel(): string
    {
        return __('Area.PluralModelLabel');
    }
}
