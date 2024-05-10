<?php

namespace App\Filament\Admin\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class SubscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'subscriptions';

    public function form(Form $form): Form
    {
        $customer_id=$this->getOwnerRecord()->id;

        return $form
            ->schema([
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number_of_records')
                    ->required()
                    ->maxLength(255),
            Forms\Components\Select::make('feature_id')
            ->relationship('feature', app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title')
            ->required()
            ->unique('subscriptions', 'feature_id',modifyRuleUsing: function ($rule) use ($customer_id) {
                $rule->where('customer_id', $customer_id);
            }),
                    
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('price')
            ->columns([
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('number_of_records'),
            Tables\Columns\TextColumn::make(app()->getLocale() == 'ar' ? 'feature.arabic_title' : 'feature.kurdish_title')
            ->label('Feature'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
