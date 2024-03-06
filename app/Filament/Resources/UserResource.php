<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $label = 'Users';

    protected static string $icon = 'heroicon-o-user';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Admin';
    public static function form(Form $form): Form
    {
        
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label(__('User.name')),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)->label(__('User.email')),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->nullable()
                    ->label(__('User.email_verified_at')),
                TextInput::make('password')
                    ->password()
                    ->maxLength(191)
                    ->dehydrateStateUsing(static fn (null|string $state): null|string =>
                    filled($state) ? Hash::make($state): null,
                    )->required(static fn (Page $livewire): bool =>
                        $livewire instanceof Pages\CreateUser,
                    )->dehydrated(static fn (null|string $state): bool =>
                    filled($state),
                    )->label(static fn (Page $livewire): string =>
                    ($livewire instanceof Pages\EditUser) ? 'كلمة المرور جديدة' : 'كلمة المرور'
                    )->label(__('User.password')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label(__('User.name')),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label(__('User.email')),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('User.created_at'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('User.updated_at'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
               
            
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
