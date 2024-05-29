<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomerResource\Pages;
use App\Filament\Admin\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Admin\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Admin\Resources\CustomerResource\Pages\ListCustomers;
use App\Filament\Admin\Resources\CustomerResource\RelationManagers;
use App\Filament\Admin\Resources\CustomerResource\RelationManagers\SubscriptionsRelationManager;

use App\Models\Customer;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\HtmlString;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-c-users';
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {


        return $form
        ->schema([
            Wizard::make([
                Wizard\Step::make('Customer Information')
                ->icon('heroicon-m-user')
                ->columnSpan(6)
                    ->schema([
                        Forms\Components\TextInput::make('arabic_title')
                        ->required()
                            ->maxLength(40)
                            ->label(__('Arabic Title')),
                        Forms\Components\TextInput::make('kurdish_title')
                        ->required()
                            ->maxLength(40)
                            ->label(__('Kurdish Title')),
                        Forms\Components\TextInput::make('contact_info')
                        ->maxLength(60)
                            ->label(__('Contact Info')),
                        Forms\Components\TextInput::make('slug')
                        ->required()
                            ->maxLength(30)
                            ->label(__('Slug')),
                        Forms\Components\FileUpload::make('logo')
                        ->image()
                            ->imageEditor()
                            ->label(__('Logo')),
                    ])
                    ->columns(2),

                Wizard\Step::make('Location Information')
                ->schema([
                    SelectTree::make('area_id')
                    ->relationship('area', app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title', 'parent_id')
                    ->placeholder(__('Please select an Area'))
                    ->required(),
                    Forms\Components\Select::make('sector_id')
                    ->relationship('sector', app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title')
                    ->label(__('Sector'))
                    ->required(),
                    Map::make('location')
                    ->defaultLocation([36.8663, 42.9884]) // default coordinates
                        ->label(__('Location')),
                ]),

                Wizard\Step::make('Admin Information')
                ->icon('heroicon-m-user-plus')
                ->schema([
                    Forms\Components\Fieldset::make('admin_id')
                    ->relationship('admin')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                        ->columnSpan(4),
                        Forms\Components\TextInput::make('email')
                        ->label(__('Email'))
                        ->required()
                        ->unique()
                        ->columnSpan(4),
                        Forms\Components\TextInput::make('password')
                        ->label(__('Password'))
                        ->required()
                        ->columnSpan(4),
                    ]),
                ]),

                Wizard\Step::make('Additional Information')
                ->icon('heroicon-m-information-circle')
                ->schema([
                    Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                        ->columnSpanFull()
                        ->label(__('Description')),
                    Forms\Components\Textarea::make('about')
                    ->maxLength(65535)
                        ->columnSpanFull()
                        ->label(__('About')),
                    Forms\Components\TextInput::make('display_order')
                    ->required()
                        ->numeric()
                        ->label(__('Display Order')),
                    Forms\Components\Toggle::make('activation_state')
                    ->required()
                        ->label(__('Activation State')),
                    Forms\Components\DatePicker::make('next_payment')
                    ->label(__('Next Payment Date')),
                ])
            ])->submitAction(new HtmlString('<button type="submit">Submit</button>'))
            ->columnSpanFull(),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('arabic_title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kurdish_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    



                Tables\Columns\TextColumn::make(app()->getLocale() == 'ar' ? 'sector.arabic_title' : 'sector.kurdish_title')
                    ->label('Sector'),

                Tables\Columns\TextColumn::make(app()->getLocale() == 'ar' ? 'area.arabic_title' : 'area.kurdish_title')
                    ->label('Area'),


                Tables\Columns\TextColumn::make('contact_info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('activation_state')
                    ->boolean(),

                Tables\Columns\ImageColumn::make('logo')->disk('public')->width('50px')->height('50px')->toggleable(isToggledHiddenByDefault: true)->label('Logo'),

                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('next_payment')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
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
            SubscriptionsRelationManager::class
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
