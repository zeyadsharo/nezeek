<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ItemResource\Pages;
use App\Filament\App\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use GalleryJsonMedia\Form\JsonMediaGallery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'I Bazzar';
    public static function form(Form $form): Form
    {
        return $form
            ->schema(

                [
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    KeyValue::make('details')
                        ->default(fn($state) => [
                            "username" => 'xx',
                            "password" => 'yy',
                        ])
                        ->keyLabel('Property name')
                        ->valueLabel('Property value')

                        ->columnSpanFull(),

                        

                    JsonMediaGallery::make('images')
                        ->directory('page')
                        ->reorderable()
                        ->preserveFilenames()
                        ->replaceNameByTitle() // If you want to show title (alt customProperties) against file name
                        ->image() // only images by default , u need to choose one (images or document)
                        // only documents (eg: pdf, doc, xls,...)
                        ->downloadable()
                        ->deletable()

                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
