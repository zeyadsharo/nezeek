<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PostResource\Pages;
use App\Filament\App\Resources\PostResource\RelationManagers;
use App\Filament\App\Resources\PostResource\Widgets\PostState;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-s-newspaper';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(50)
                ->label('Post Title'),

            FileUpload::make('cover_image')
                ->disk('public')
                ->directory('posts')
                ->image()
                ->imageEditor()
                ->label('Cover Image'),

            Forms\Components\DatePicker::make('post_date')
                ->required()
                ->default(now())
                ->label('Post Date'),

            Forms\Components\TextInput::make('display_order')
                ->required()
                ->numeric()
                ->default(0)
                ->label('Display Order'),

            RichEditor::make('content')
                ->toolbarButtons([
                    'attachFiles',
                    'blockquote',
                    'bold',
                    'bulletList',
                    'codeBlock',
                    'h2',
                    'h3',
                    'italic',
                    'link',
                    'orderedList',
                    'redo',
                    'strike',
                    'underline',
                    'undo',
                ])
                ->columnSpanFull(),

            Forms\Components\DatePicker::make('auto_delete_at')
                ->minDate(now())
                ->default(now()->addYear())
                ->weekStartsOnSunday()
                ->label('Auto Delete At'),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('cover_image')
                    ->disk('public')
                    ->width('50px')->height('50px')
                    ->label('Cover Image'),
                Tables\Columns\TextColumn::make('post_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('auto_delete_at')
                    ->date()
                    ->sortable(),
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
                Tables\Actions\ViewAction::make()
                ->slideOver(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ])
            ->recordUrl(null);
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
    public static function getWidgets(): array
    {
        return [
           PostState::class,
        ];
    }
    //getHeaderWidgets
    public static function getHeaderWidgets(): array
    {
        return [
            PostState::class,
        ];
    }
}
