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

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    protected static ?int $navigationSort = 1;

    //label
    protected static ?string $navigationLabel = 'المناطق';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('العنوان'),

                Forms\Components\Select::make('parent_id')
                    ->relationship('parentArea', 'title')
                    ->searchable()
                    ->preload()
                    ->label('المنطقة الأب'),

                Forms\Components\TextInput::make('latitude')
                    ->numeric()
                    ->label('خط العرض'),

                Forms\Components\TextInput::make('longitude')
                    ->numeric()
                    ->label('خط الطول'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable()
                    ->label('المعرف'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('العنوان'),

                Tables\Columns\TextColumn::make('parentArea.title')
                    ->searchable()
                    ->sortable()
                    ->label('المنطقة الأب'),

                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable()
                    ->label('خط العرض'),

                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
                    ->sortable()
                    ->label('خط الطول'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('تاريخ الإنشاء'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('تاريخ التحديث'),
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
