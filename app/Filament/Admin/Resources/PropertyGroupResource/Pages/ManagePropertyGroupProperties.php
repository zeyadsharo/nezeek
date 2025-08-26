<?php

namespace App\Filament\Admin\Resources\PropertyGroupResource\Pages;

use App\Filament\Admin\Resources\PropertyGroupResource;
use App\Models\Property;
use App\Models\PropertyGroup;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class ManagePropertyGroupProperties extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PropertyGroupResource::class;

    protected static string $view = 'filament.admin.resources.property-group-resource.pages.manage-property-group-properties';

    public ?PropertyGroup $record = null;

    public ?array $data = [];

    public function mount(PropertyGroup $record): void
    {
        $this->record = $record;
        $this->form->fill($this->getFormData());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('إدارة خصائص المجموعة')
                    ->tabs([
                        Tab::make('خصائص المجموعة')
                            ->schema([
                                Section::make('إدارة الخصائص')
                                    ->description('إرفاق الخصائص بهذه المجموعة وتنظيمها')
                                    ->schema([
                                        Repeater::make('group_properties')
                                            ->label('خصائص المجموعة')
                                            ->relationship('properties')
                                            ->schema([
                                                Select::make('property_id')
                                                    ->label('الخاصية')
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
                                                            }
                                                        }
                                                    }),

                                                TextInput::make('custom_label')
                                                    ->label('التسمية المخصصة')
                                                    ->maxLength(255)
                                                    ->helperText('تجاوز التسمية الافتراضية للخاصية في هذه المجموعة'),

                                                Textarea::make('custom_help_text')
                                                    ->label('نص المساعدة المخصص')
                                                    ->maxLength(500)
                                                    ->rows(2)
                                                    ->helperText('تجاوز نص المساعدة الافتراضي في هذه المجموعة'),

                                                TextInput::make('display_order')
                                                    ->label('ترتيب العرض')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->helperText('الترتيب داخل المجموعة'),

                                                Toggle::make('is_visible')
                                                    ->label('مرئي')
                                                    ->default(true)
                                                    ->helperText('إظهار هذه الخاصية للمستخدمين'),

                                                Toggle::make('is_editable')
                                                    ->label('قابل للتعديل')
                                                    ->default(true)
                                                    ->helperText('السماح للمستخدمين بتعديل هذه الخاصية'),

                                                Toggle::make('is_required')
                                                    ->label('مطلوب')
                                                    ->default(false)
                                                    ->helperText('جعل هذه الخاصية إلزامية'),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->addActionLabel('إضافة خاصية')
                                            ->reorderableWithButtons()
                                            ->collapsible()
                                            ->itemLabel(
                                                fn(array $state): ?string =>
                                                Property::find($state['property_id'] ?? null)?->title ?? 'خاصية جديدة'
                                            ),

                                        Section::make('التعيين السريع للخصائص')
                                            ->description('إرفاق عدة خصائص بهذه المجموعة بسرعة')
                                            ->schema([
                                                Select::make('quick_properties')
                                                    ->label('اختر الخصائص')
                                                    ->multiple()
                                                    ->options(function () {
                                                        // Get properties that are not already assigned to this group
                                                        $assignedPropertyIds = $this->record->properties()->pluck('properties.id')->toArray();
                                                        return Property::whereNotIn('id', $assignedPropertyIds)
                                                            ->pluck('title', 'id');
                                                    })
                                                    ->searchable()
                                                    ->preload()
                                                    ->placeholder('اختر الخصائص للإضافة')
                                                    ->helperText('يظهر فقط الخصائص غير المرفقة بالمجموعة'),

                                                Toggle::make('make_required')
                                                    ->label('جعل مطلوب')
                                                    ->default(false)
                                                    ->helperText('جعل جميع الخصائص المحددة إلزامية'),

                                                Toggle::make('make_visible')
                                                    ->label('جعل مرئي')
                                                    ->default(true)
                                                    ->helperText('جعل جميع الخصائص المحددة مرئية'),
                                            ])->columns(2),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function getFormData(): array
    {
        $properties = $this->record->properties()->withPivot([
            'custom_label',
            'custom_help_text',
            'display_order',
            'is_visible',
            'is_editable',
            'is_required'
        ])->get();

        $formData = [];
        foreach ($properties as $property) {
            $formData['group_properties'][] = [
                'property_id' => $property->id,
                'custom_label' => $property->pivot->custom_label,
                'custom_help_text' => $property->pivot->custom_help_text,
                'display_order' => $property->pivot->display_order,
                'is_visible' => $property->pivot->is_visible,
                'is_editable' => $property->pivot->is_editable,
                'is_required' => $property->pivot->is_required,
            ];
        }

        return $formData;
    }

    public function saveProperties(): void
    {
        $data = $this->form->getState();

        if (isset($data['group_properties'])) {
            $properties = [];
            foreach ($data['group_properties'] as $propertyData) {
                if (isset($propertyData['property_id'])) {
                    $properties[$propertyData['property_id']] = [
                        'custom_label' => $propertyData['custom_label'] ?? null,
                        'custom_help_text' => $propertyData['custom_help_text'] ?? null,
                        'display_order' => $propertyData['display_order'] ?? 0,
                        'is_visible' => $propertyData['is_visible'] ?? true,
                        'is_editable' => $propertyData['is_editable'] ?? true,
                        'is_required' => $propertyData['is_required'] ?? false,
                    ];
                }
            }

            // Sync properties (this will update existing and add new ones)
            $this->record->properties()->sync($properties);

            Notification::make()
                ->title('تم تحديث الخصائص بنجاح')
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
                    'display_order' => 0,
                    'is_visible' => $data['make_visible'] ?? true,
                    'is_editable' => true,
                    'is_required' => $data['make_required'] ?? false,
                    'custom_label' => null,
                    'custom_help_text' => null,
                ];
            }

            // Use syncWithoutDetaching to avoid duplicate key errors
            $this->record->properties()->syncWithoutDetaching($properties);

            Notification::make()
                ->title('تم تعيين الخصائص بنجاح')
                ->success()
                ->send();

            // Refresh the form data
            $this->form->fill($this->getFormData());
        }
    }
}
