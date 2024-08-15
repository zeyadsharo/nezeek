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
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-s-newspaper';

    public static function getNavigationLabel(): string
    {
        return __('Post.Post');
    }
    public static function getModelLabel(): string
    {
        return __('Post.Post');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Post.Posts');
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(50)
                ->label(__('Post.Title')),

            FileUpload::make('cover_image')
                ->disk('public')
                ->directory('posts')
                ->image()
                ->imageEditor()
                ->label(__('Post.Cover Image')),

            Forms\Components\DatePicker::make('post_date')
                ->required()
                ->default(now())
                ->label(__('Post.Post Date')),

            Forms\Components\TextInput::make('display_order')
                ->required()
                ->numeric()
                ->default(0)
                ->label(__('Post.Display Order')),

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
                ->columnSpanFull()
                ->label(__('Post.Content'))
                ->required(),

            Forms\Components\DatePicker::make('auto_delete_at')
                ->minDate(now())
                ->default(now()->addYear())
                ->weekStartsOnSunday()
                ->label(__('Post.Auto Delete At')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            
            ->columns([
                Split::make([
                    ImageColumn::make('cover_image')
                        ->disk('public')
                        ->circular()
                        ->disk('public')
                        ->width('50px')
                        ->height('50px')
                        ->label(__('Post.Cover Image'))
                        ->grow(false),
                    Stack::make([
                        TextColumn::make('title')
                            ->weight(FontWeight::Bold)
                            ->searchable(),
                        TextColumn::make('post_date')
                            ->date()
                            ->icon('heroicon-m-calendar')
                            ->label(__('Post.Post Date')),
                    ])->space(1)
                ])->from('md'),

            ])
            ->filters([
                // Define your filters here
            ])
            ->paginated(false)
            ->searchable(true)
            ->defaultSort('post_date', 'desc')
            ->paginatedWhileReordering()
            ->deferLoading()
            ->actions([
                ViewAction::make()->slideOver()->label(''),
                EditAction::make()->label(''),

            ])
            ->bulkActions([
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])->recordUrl(null);
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
   
    // }
}
