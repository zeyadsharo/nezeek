<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ItemResource\Pages;
use App\Filament\App\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use GalleryJsonMedia\Form\JsonMediaGallery;
use GalleryJsonMedia\Tables\Columns\JsonMediaColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-m-document';
    protected static ?string $navigationGroup = 'I Bazzar';
    public static function form(Form $form): Form
    {
        return $form
            ->schema(

                [
                TextInput::make('title')
                ->required()
                    ->maxLength(40)
                    ->label(__('Title'))  
                    ->placeholder(__('Enter title')),  

                Select::make('department_id')
                ->required()  
                    ->relationship(name: 'department', titleAttribute: app()->getLocale() == 'ar' ? 'title_ar' : 'title_ku')
                    ->label(__('Department')) 
                    ->placeholder(__('Select Department')), 

                    KeyValue::make('details')
                        ->default(fn ($state) => [
                            "Model" => '',
                            "Type" => 'yy',
                        ])
                        ->keyLabel('Property name')
                        ->valueLabel('Property value')

                        ->columnSpanFull()
                    ->rules(['json']),



                    JsonMediaGallery::make('images')
                        ->directory('page')
                        ->reorderable()
                        ->preserveFilenames()
                        ->replaceNameByTitle()
                        ->image()
                        ->downloadable()
                        ->maxFiles(4)   
                    ->maxSize(5120)
                        ->deletable()
                        ->label(__('Images'))

                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            JsonMediaColumn::make('images')
                ->avatars(),
                Tables\Columns\TextColumn::make('title')->label('Title'),
                Tables\Columns\TextColumn::make("department.title_ar")->label('Department'),


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
