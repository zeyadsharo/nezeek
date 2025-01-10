<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Admin\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Admin\Resources\CustomerResource\Pages\ListCustomers;
use App\Filament\Admin\Resources\CustomerResource\RelationManagers\SubscriptionsRelationManager;
use App\Models\Area;
use App\Models\Customer;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-c-users';
    protected static ?string $navigationGroup = 'Admin';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Customer.Customers');
    }
    public static function getModelLabel(): string
    {
        return __('Customer.Customer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Customer.Customers');
    }
    public static function form(Form $form): Form
    {


        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make(__('Customer.step.customer_information'))
                        ->icon('heroicon-m-user')
                        ->columnSpan(6)
                        ->schema([
                            Forms\Components\TextInput::make('arabic_title')
                                ->required()
                                ->maxLength(40)
                                ->label(__('Customer.label.arabic_title')),
                            Forms\Components\TextInput::make('kurdish_title')
                                ->required()
                                ->maxLength(40)
                                ->label(__('Customer.label.kurdish_title')),
                            Forms\Components\TextInput::make('contact_info')
                                ->maxLength(60)
                                ->label(__('Customer.label.contact_info')),
                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->maxLength(30)
                                ->label(__('Customer.label.slug')),
                            Forms\Components\FileUpload::make('logo')
                                ->image()
                                ->imageEditor()
                                ->label(__('Customer.label.logo')),
                        ])
                        ->columns(2),

                    Wizard\Step::make(__('Customer.step.location_information'))
                        ->schema([
                            SelectTree::make('area_id')
                                ->reactive()
                                ->relationship('area', app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title', 'parent_id')
                                ->placeholder(__('Customer.label.select_area'))
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $parentArea = Area::find($state);
                                    if ($parentArea) {
                                        $set('location', [
                                            'lat' => floatval($parentArea->latitude),
                                            'lng' => floatVal($parentArea->longitude),
                                        ]);
                                    }
                                })
                                // disable the parent id is null or o
                                ->disabled(function ($record) {
                                    if ($record) {
                                        return $record->parent_id == null;
                                    }
                                })
                                ->required(),
                            Forms\Components\Select::make('sector_id')
                                ->relationship('sector', app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title')
                                ->label(__('Customer.label.sector'))
                                ->required(),
                            Map::make('location')
                                ->mapControls([
                                    'mapTypeControl'    => true,
                                    'scaleControl'      => true,
                                    'streetViewControl' => true,
                                    'rotateControl'     => true,
                                    'fullscreenControl' => true,
                                    'searchBoxControl'  => false, // creates geocomplete field inside map
                                    'zoomControl'       => true,

                                ])
                                ->draggable()
                                ->reverseGeocode([
                                    'city'   => '%L',
                                    'zip'    => '%z',
                                    'state'  => '%A1',
                                    'street' => '%n %S',
                                ])
                                ->live()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $set('latitude', round($state['lat'], 6));
                                    $set('longitude', round($state['lng'], precision: 6));
                                })
                                ->clickable(true)
                                ->defaultZoom(5)
                                ->defaultLocation([36.8663, 42.9884])
                                ->geolocate() // adds a button to request device location and set map marker accordingly
                                ->geolocateLabel(__('Customer.get_location')) // overrides the default label for geolocate button
                                ->geolocateOnLoad(true, false) // geolocate on load, second arg 'always' (default false, only for new form))
                                ->layers([
                                    'https://googlearchive.github.io/js-v2-samples/ggeoxml/cta.kml',
                                ]) // array of KML layer URLs to add to the map
                                ->geoJson('https://fgm.test/storage/AGEBS01.geojson') // GeoJSON file, URL or JSON
                                ->geoJsonContainsField('geojson') // field to capture GeoJSON polygon(s) which contain the map marker // default coordinates
                                ->label(__('Customer.label.location')),
                        ]),

                    Wizard\Step::make(__('Customer.step.admin_information'))
                        ->icon('heroicon-m-user-plus')
                        ->schema([
                            Forms\Components\Fieldset::make('admin_id')
                                ->relationship('admin')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label(__('Customer.label.admin_name'))
                                        ->columnSpan(4),
                                    Forms\Components\TextInput::make('email')
                                        ->label(__('Customer.label.admin_email'))
                                        ->required()
                                        ->unique()
                                        ->columnSpan(4),
                                    Forms\Components\TextInput::make('password')
                                        ->label(__('Customer.label.admin_password'))
                                        ->required()
                                        ->columnSpan(4),
                                ]),
                        ]),

                    Wizard\Step::make(__('Customer.step.additional_information'))
                        ->icon('heroicon-m-information-circle')
                        ->schema([
                            Forms\Components\Textarea::make('description')
                                ->maxLength(65535)
                                ->columnSpanFull()
                                ->label(__('Customer.label.description')),
                            Forms\Components\Textarea::make('about')
                                ->maxLength(65535)
                                ->columnSpanFull()
                                ->label(__('Customer.label.about')),
                            Forms\Components\TextInput::make('display_order')
                                ->required()
                                ->default(1)
                                ->numeric()
                                ->label(__('Customer.label.display_order')),
                            Forms\Components\Toggle::make('activation_state')
                                ->required()
                                ->default(true)
                                ->label(__('Customer.label.activation_state')),
                            Forms\Components\DatePicker::make('next_payment')
                                ->default(now()->addMonths(3))
                                ->label(__('Customer.label.next_payment')),
                        ])
                ])->submitAction(new HtmlString('<button type="submit">' . __('Customer.submit') . '</button>'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('arabic_title')
                    ->label(__('Customer.label.arabic_title'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('kurdish_title')
                    ->label(__('Customer.label.kurdish_title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('display_order')
                    ->label(__('Customer.label.display_order'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),




                Tables\Columns\TextColumn::make(app()->getLocale() == 'ar' ? 'sector.arabic_title' : 'sector.kurdish_title')
                    ->label(__('Customer.label.sector')),

                Tables\Columns\TextColumn::make(app()->getLocale() == 'ar' ? 'area.arabic_title' : 'area.kurdish_title')
                    ->label(__('Customer.label.area')),

                Tables\Columns\TextColumn::make('contact_info')
                    ->label(__('Customer.label.contact_info'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('Customer.label.slug'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('activation_state')
                    ->label(__('Customer.label.activation_state'))
                    ->boolean(),

                Tables\Columns\ImageColumn::make('logo')->disk('public')->width('50px')->height('50px')->toggleable(isToggledHiddenByDefault: true)->label(__('Customer.label.logo')),

                Tables\Columns\TextColumn::make('latitude')
                    ->label(__('Customer.label.latitude'))
                    ->numeric()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('longitude')
                    ->label(__('Customer.label.longitude'))
                    ->numeric()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('next_payment')
                    ->label(__('Customer.label.next_payment'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Customer.label.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sector_id')
                    ->label(__('Customer.filter.sector'))
                    ->relationship('sector', app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title'),
                Tables\Filters\Filter::make('area_tree')
                    ->form([
                        SelectTree::make('areas')
                            ->relationship(
                                'area',
                                app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title',
                                'parent_id'
                            )
                            ->label(__('Customer.filter.area'))
                            ->independent(false)
                            ->enableBranchNode(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['areas'], function ($query, $areas) {
                            return $query->whereHas('area', fn($query) => $query->where('id', $areas));
                        });
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['areas']) {
                            return null;
                        }

                        return __('Area.ModelLabel') . ': ' . implode(
                            ', ',
                            Area::where('id', $data['areas'])
                                ->get()
                                ->pluck(app()->getLocale() == 'ar' ? 'arabic_title' : 'kurdish_title')
                                ->toArray()
                        );
                    })

            ], FiltersLayout::AboveContent)

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
