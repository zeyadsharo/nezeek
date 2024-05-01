<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\DepartmentResource\Pages;
use App\Filament\App\Resources\DepartmentResource\RelationManagers;
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

    protected static ?string $navigationIcon = 'heroicon-m-users';
    protected static ?string $navigationGroup = 'I Bazzar';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([Forms\Components\TextInput::make('title_ar')->label('Title (Arabic)'),
            Forms\Components\TextInput::make('title_ku')->label('Title (Kurdish)'),
                Forms\Components\TextInput::make('display_order')->label('Display Order'),

                FileUpload::make('icon')->disk('public')
                    ->directory('department_categories')
                    ->image()
                    ->disk('public')
                    ->imageEditor()
                    ->label('Icon'),
                Select::make('category_id')
                    ->placeholder(__('Select Category'))
                    ->relationship(name: 'category', titleAttribute: app()->getLocale() == 'ar' ? 'title_ar' : 'title_ku'),

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
