<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DepartmentCategoryResource\Pages;
use App\Filament\App\Resources\DepartmentCategoryResource\RelationManagers;
use App\Models\DepartmentCategory;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentCategoryResource extends Resource
{
    protected static ?string $model = DepartmentCategory::class;

    protected static ?string $navigationIcon = 'heroicon-c-archive-box';
    protected static ?string $navigationGroup = 'I Bazzar';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title_ar')
                    ->label(__('Title (Arabic)'))
                    ->required()
                    ->maxLength(40)
                    ->rules(['string']),
                Forms\Components\TextInput::make('title_ku')
                    ->label(__('Title (Kurdish)'))
                    ->required()
                    ->maxLength(40)
                    ->rules(['string']),
                Forms\Components\TextInput::make('display_order')
                    ->label(__('Display Order'))
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\FileUpload::make('icon')
                    ->label(__('Icon'))
                    ->disk('public')
                    ->directory('department_categories')
                    ->image()
                    ->imageEditor()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->disk('public')
                    ->width('50px')->height('50px')
                    ->label('Icon'),
                Tables\Columns\TextColumn::make('display_order')->label('Display Order'),
                Tables\Columns\TextColumn::make('title_ar')->label('Title (Arabic)'),
                Tables\Columns\TextColumn::make('title_ku')->label('Title (Kurdish)'),
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
            'index' => Pages\ListDepartmentCategories::route('/'),
            'create' => Pages\CreateDepartmentCategory::route('/create'),
            'edit' => Pages\EditDepartmentCategory::route('/{record}/edit'),
        ];
    }
}
