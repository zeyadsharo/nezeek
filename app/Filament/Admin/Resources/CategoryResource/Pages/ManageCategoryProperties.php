<?php

namespace App\Filament\Admin\Resources\CategoryResource\Pages;

use App\Filament\Admin\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyGroup;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class ManageCategoryProperties extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = CategoryResource::class;

    protected static string $view = 'filament.admin.resources.category-resource.pages.manage-category-properties';

    public ?Category $record = null;

    public ?array $data = [];

    public function mount(Category $record): void
    {
        $this->record = $record;
        $this->form->fill($this->getFormData());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Current Properties')
                    ->description('Manage properties currently assigned to this category')
                    ->schema([
                        Repeater::make('category_properties')
                            ->label('Category Properties')
                            ->schema([
                                Select::make('property_id')
                                    ->label('Property')
                                    ->options(Property::pluck('title', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $property = Property::find($state);
                                            if ($property) {
                                                $set('custom_label', $property->title);
                                                $set('custom_help_text', $property->description);
                                                $set('custom_validation_rules', $property->validation_rules);
                                            }
                                        }
                                    }),

                                Select::make('property_group_id')
                                    ->label('Property Group')
                                    ->options(PropertyGroup::pluck('title', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select group (optional)'),

                                TextInput::make('custom_label')
                                    ->label('Custom Label')
                                    ->maxLength(255)
                                    ->helperText('Override the default property label for this category'),

                                Textarea::make('custom_help_text')
                                    ->label('Custom Help Text')
                                    ->maxLength(500)
                                    ->rows(2)
                                    ->helperText('Override the default help text for this category'),

                                TextInput::make('custom_validation_rules')
                                    ->label('Custom Validation Rules')
                                    ->maxLength(255)
                                    ->helperText('Override the default validation rules for this category'),

                                TextInput::make('display_order')
                                    ->label('Display Order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Order within the group'),

                                Toggle::make('is_visible')
                                    ->label('Visible')
                                    ->default(true)
                                    ->helperText('Show this property to users'),

                                Toggle::make('is_editable')
                                    ->label('Editable')
                                    ->default(true)
                                    ->helperText('Allow users to edit this property'),

                                Toggle::make('is_required')
                                    ->label('Required')
                                    ->default(false)
                                    ->helperText('Make this property mandatory'),

                                Textarea::make('custom_options')
                                    ->label('Custom Options')
                                    ->helperText('Override property options for this category (JSON format)')
                                    ->placeholder('{"option1": "value1", "option2": "value2"}')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Textarea::make('show_when')
                                    ->label('Show When')
                                    ->helperText('Conditional logic to show this property (JSON format)')
                                    ->placeholder('{"property": "value"}')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Textarea::make('hide_when')
                                    ->label('Hide When')
                                    ->helperText('Conditional logic to hide this property (JSON format)')
                                    ->placeholder('{"property": "value"}')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Property')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(
                                fn(array $state): ?string =>
                                Property::find($state['property_id'] ?? null)?->title ?? 'New Property'
                            ),

                        Actions::make([
                            Action::make('save_properties')
                                ->label('Save Changes')
                                ->icon('heroicon-o-check')
                                ->color('success')
                                ->action(function (array $data): void {
                                    $this->saveProperties($data);
                                }),
                        ]),
                    ]),

                Section::make('Quick Property Assignment')
                    ->description('Quickly assign multiple properties to this category')
                    ->schema([
                        Select::make('quick_properties')
                            ->label('Select Properties')
                            ->multiple()
                            ->options(function () {
                                // Get properties that are not already assigned to this category
                                $assignedPropertyIds = $this->record->properties()->pluck('properties.id')->toArray();
                                return Property::whereNotIn('id', $assignedPropertyIds)
                                    ->pluck('title', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('Choose properties to add')
                            ->helperText('Only shows properties not already assigned to this category'),

                        Select::make('default_property_group')
                            ->label('Default Group')
                            ->options(PropertyGroup::pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('Select default group for quick assignments')
                            ->helperText('Properties will be assigned to this group by default'),

                        Toggle::make('make_required')
                            ->label('Make Required')
                            ->default(false)
                            ->helperText('Make all selected properties required'),

                        Toggle::make('make_visible')
                            ->label('Make Visible')
                            ->default(true)
                            ->helperText('Make all selected properties visible'),

                        Actions::make([
                            Action::make('assign_properties')
                                ->label('Assign Selected Properties')
                                ->icon('heroicon-o-plus-circle')
                                ->color('primary')
                                ->action(function (array $data): void {
                                    $this->assignProperties($data);
                                })
                                ->requiresConfirmation()
                                ->modalHeading('Assign Properties')
                                ->modalDescription('Are you sure you want to assign the selected properties to this category?')
                                ->modalSubmitActionLabel('Yes, assign properties'),
                        ]),
                    ])->columns(2),
            ]);
    }

    protected function getFormData(): array
    {
        $properties = $this->record->properties()->withPivot([
            'property_group_id',
            'display_order',
            'is_visible',
            'is_editable',
            'is_required',
            'custom_label',
            'custom_help_text',
            'custom_validation_rules',
            'custom_options',
            'show_when',
            'hide_when'
        ])->get();

        $formData = [];
        foreach ($properties as $property) {
            $formData['category_properties'][] = [
                'property_id' => $property->id,
                'property_group_id' => $property->pivot->property_group_id,
                'custom_label' => $property->pivot->custom_label,
                'custom_help_text' => $property->pivot->custom_help_text,
                'custom_validation_rules' => $property->pivot->custom_validation_rules,
                'display_order' => $property->pivot->display_order,
                'is_visible' => $property->pivot->is_visible,
                'is_editable' => $property->pivot->is_editable,
                'is_required' => $property->pivot->is_required,
                'custom_options' => $property->pivot->custom_options,
                'show_when' => $property->pivot->show_when,
                'hide_when' => $property->pivot->hide_when,
            ];
        }

        return $formData;
    }

    protected function saveProperties(array $data): void
    {
        if (isset($data['category_properties'])) {
            $properties = [];
            foreach ($data['category_properties'] as $propertyData) {
                if (isset($propertyData['property_id'])) {
                    $properties[$propertyData['property_id']] = [
                        'property_group_id' => $propertyData['property_group_id'] ?? null,
                        'display_order' => $propertyData['display_order'] ?? 0,
                        'is_visible' => $propertyData['is_visible'] ?? true,
                        'is_editable' => $propertyData['is_editable'] ?? true,
                        'is_required' => $propertyData['is_required'] ?? false,
                        'custom_label' => $propertyData['custom_label'] ?? null,
                        'custom_help_text' => $propertyData['custom_help_text'] ?? null,
                        'custom_validation_rules' => $propertyData['custom_validation_rules'] ?? null,
                        'custom_options' => $propertyData['custom_options'] ?? null,
                        'show_when' => $propertyData['show_when'] ?? null,
                        'hide_when' => $propertyData['hide_when'] ?? null,
                    ];
                }
            }

            // Sync properties (this will update existing and add new ones)
            $this->record->properties()->sync($properties);

            Notification::make()
                ->title('Properties updated successfully')
                ->success()
                ->send();
        }
    }

    protected function assignProperties(array $data): void
    {
        if (!empty($data['quick_properties'])) {
            $properties = [];
            foreach ($data['quick_properties'] as $propertyId) {
                $properties[$propertyId] = [
                    'property_group_id' => $data['default_property_group'] ?? null,
                    'display_order' => 0,
                    'is_visible' => $data['make_visible'] ?? true,
                    'is_editable' => true,
                    'is_required' => $data['make_required'] ?? false,
                    'custom_label' => null,
                    'custom_help_text' => null,
                    'custom_validation_rules' => null,
                    'custom_options' => null,
                    'show_when' => null,
                    'hide_when' => null,
                ];
            }

            // Use syncWithoutDetaching to avoid duplicate key errors
            $this->record->properties()->syncWithoutDetaching($properties);

            Notification::make()
                ->title('Properties assigned successfully')
                ->success()
                ->send();

            // Refresh the form data
            $this->form->fill($this->getFormData());
        }
    }

    public function getTitle(): string
    {
        return "Manage Properties: {$this->record->title}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'Categories' => route('filament.admin.resources.categories.index'),
            $this->record->title => route('filament.admin.resources.categories.edit', $this->record),
            'Manage Properties',
        ];
    }
}
