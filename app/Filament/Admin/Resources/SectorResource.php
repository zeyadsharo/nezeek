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

    protected static ?string $navigationIcon = 'heroicon-c-tag';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('arabic_title')
                ->required()
                ->maxLength(40)  // Adjusted as per your requirement
                ->label(__('Arabic Title'))  // Translation for label
                ->placeholder(__('Enter Arabic title'))  // Placeholder with translation
                ->rules(['string']),  // Ensure the input is a string

            Forms\Components\TextInput::make('kurdish_title')
                ->required()
                ->maxLength(40)
                ->label(__('Kurdish Title'))  
                ->placeholder(__('Enter Kurdish title')) 
                ->rules(['string']),  

            Forms\Components\Textarea::make('description')
                ->maxLength(200)
                ->columnSpanFull()
                ->label(__('Description'))  
                ->placeholder(__('Enter description')), 

            Forms\Components\TextInput::make('display_order')
                ->required()
                ->numeric()
                ->default(0)
                ->label(__('Display Order'))  
                ->placeholder(__('Enter display order')), 

            Forms\Components\FileUpload::make('icon')
                ->label(__('Icon'))
                ->disk('public')
                ->directory('sectors')
                ->image()
                ->imageEditor()
                ->placeholder(__('Enter icon class or path'))
                ->required(),

            Forms\Components\Toggle::make('display_state')
                ->required()
                ->inline()
                ->default(true)
                ->label(__('Display State')), 

            Forms\Components\Toggle::make('activation_state')
                ->required()
                ->inline()
                ->default(true)
                ->label(__('Activation State')) 
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('arabic_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kurdish_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('display_state')
                    ->boolean(),
                Tables\Columns\TextColumn::make('icon')
                    ->searchable(),
                Tables\Columns\IconColumn::make('activation_state')
                    ->boolean(),
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
            'index' => Pages\ListSectors::route('/'),
            'create' => Pages\CreateSector::route('/create'),
            'edit' => Pages\EditSector::route('/{record}/edit'),
        ];
    }
}
