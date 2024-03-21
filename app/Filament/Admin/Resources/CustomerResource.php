<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomerResource\Pages;
use App\Filament\Admin\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Admin\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Admin\Resources\CustomerResource\RelationManagers;
use App\Filament\Resources\Admin\CustomerResource\Pages\ListCustomers;
use App\Models\Customer;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
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
                            // Forms\Components\TextInput::make('logo')
                            //     ->maxLength(255),
                            FileUpload::make('logo')
                                ->image()
                                ->imageEditor()
                        ])
                        ->columns(2),

                    Wizard\Step::make('Location Information')
                        // ->description('Customer location details')
                        ->schema([
                           
                    SelectTree::make('area_id')
                    ->relationship('area', app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title', 'parent_id')
                    ->placeholder(__('Please select a Area'))
                    ->label('Area')
                    ->required(),
                           Forms\Components\Select::make('sector_id')
                            ->relationship('sector', app()->getLocale()=='ar'?'arabic_title':'kurdish_title')
                                ->required(),
                                Map::make('location')
                        // ->mapControls([
                        //     'mapTypeControl'    => true,
                        //     'scaleControl'      => true,
                        //     'streetViewControl' => true,
                        //     'rotateControl'     => true,
                        //     'fullscreenControl' => true,
                        //     'searchBoxControl'  => false, // creates geocomplete field inside map
                        //     'zoomControl'       => false,
                        // ])
                        // ->height(fn () => '400px') // map height (width is controlled by Filament options)
                        // ->defaultZoom(10) // default zoom level when opening form
                        // ->autocomplete('full_address') // field on form to use as Places geocompletion field
                        // ->autocompleteReverse(true) // reverse geocode marker location to autocomplete field
                        // ->reverseGeocode([
                        //     'street' => '%n %S',
                        //     'city' => '%L',
                        //     'state' => '%A1',
                        //     'zip' => '%z',
                        // ]) // reverse geocode marker location to form fields, see notes below
                        //->debug() // prints reverse geocode format strings to the debug console 
                        ->defaultLocation([36.8663, 42.9884]) // default for new forms
                        // ->draggable() // allow dragging to move marker
                        // ->clickable(false) // allow clicking to move marker
                        // ->geolocate() // adds a button to request device location and set map marker accordingly
                        // ->geolocateLabel('Duhok') // overrides the default label for geolocate button
                        // ->geolocateOnLoad(true, false) // geolocate on load, second arg 'always' (default false, only for new form),
                        ]),
                    //step for add admin for customer
                    Wizard\Step::make('Admin Information')
                        
                        ->icon('heroicon-m-user-plus')
                        ->schema([

                    Relation::make('admin_id')
                    ->title('Admin')
                    ->placeholder('Select an admin')
                    ->relation('members', 'id', 'name')

                        ]),

                    //step for add subscription for customer
                    Wizard\Step::make('Subscription Information')
                        // ->description('Subscription details for the customer')
                        ->icon('heroicon-m-currency-dollar')
                        ->schema([]),

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
                
                Tables\Columns\TextColumn::make('arabic_title')
                ->searchable(),

            Tables\Columns\TextColumn::make('kurdish_title')
                ->searchable(),
                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                

                Tables\Columns\TextColumn::make(app()->getLocale()=='ar'?'sector.arabic_title':'sector.kurdish_title')
                    ->label('Sector'),
                    
                    Tables\Columns\TextColumn::make(app()->getLocale()=='ar'?'area.arabic_title':'area.kurdish_title')
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

                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
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
