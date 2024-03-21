<?php

namespace App\Filament\Admin\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'Members';

    protected function getFormSchema(): array
    {
        return [
            // Define fields for adding a new member
            Forms\Components\TextInput::make('name')
            ->required(),
            Forms\Components\TextInput::make('email')
            ->email()
                ->required(),
            Forms\Components\TextInput::make('password')
            ->password()
                ->required(),
            // other fields as necessary
        ];
    }
}
