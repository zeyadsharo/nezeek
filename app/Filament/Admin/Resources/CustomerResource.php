<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomerResource\Pages;
use App\Filament\Admin\Resources\CustomerResource\Pages\CreateCustomer;
                use App\Filament\Admin\Resources\CustomerResource\Pages\EditCustomer;
                use App\Filament\Admin\Resources\CustomerResource\RelationManagers;
use App\Filament\Resources\Admin\CustomerResource\Pages\ListCustomers;
                use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
 use Filament\Forms\Components\Section;
                use Filament\Forms\Components\Actions\Action;
                use Filament\Support\Enums\Alignment;
                 use Filament\Forms\Components\Wizard;
                 use Illuminate\Support\HtmlString;
class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {
   

    return $form
        ->schema([
            Wizard::make([
                Wizard\Step::make('Customer Information')
                    // ->description('Basic customer information')
                    ->icon('heroicon-m-user')
                    ->columnSpan(6)
                    ->schema([
                        Forms\Components\TextInput::make('arabic_title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kurdish_title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_info')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('logo')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Wizard\Step::make('Location Information')
                    // ->description('Customer location details')
                    ->schema([
                        Forms\Components\TextInput::make('area_id')
                            ->numeric(),
                        Forms\Components\TextInput::make('sector_id')
                            ->numeric(),
                        Forms\Components\TextInput::make('latitude')
                            ->numeric(),
                        Forms\Components\TextInput::make('longitude')
                            ->numeric(),
                    ]),

                Wizard\Step::make('Additional Information')
                    // ->description('Additional details about the customer')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('about')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('display_order')
                            ->required()
                            ->numeric(),
                        Forms\Components\Toggle::make('activation_state')
                            ->required(),
                        Forms\Components\DatePicker::make('next_payment'),
                    ])
            ])->submitAction(new HtmlString('<button type="submit">Submit</button>'))
            ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('area_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sector_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('arabic_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kurdish_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\IconColumn::make('activation_state')
                    ->boolean(),
                Tables\Columns\TextColumn::make('logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('next_payment')
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
