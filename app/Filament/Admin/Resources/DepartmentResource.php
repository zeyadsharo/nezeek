<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DepartmentResource\Pages;
use App\Filament\Admin\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-c-cube';
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
                    ->required(),

                Forms\Components\FileUpload::make('icon')
                    ->label(__('Icon'))
                    ->disk('public')
                    ->directory('department_categories')
                    ->image()
                    ->imageEditor()
                    ->required(),

                Forms\Components\Select::make('category_id')
                    ->label(__('Select Category'))
                    ->placeholder(__('Select Category'))
                    ->relationship('category', app()->getLocale() == 'ar' ? 'title_ar' : 'title_ku')
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
                Tables\Columns\TextColumn::make('category.title_ar')->label('Category'),
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
