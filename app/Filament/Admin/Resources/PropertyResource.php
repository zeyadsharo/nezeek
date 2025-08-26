<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropertyResource\Pages;
use App\Models\Property;
use App\Models\PropertyGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    protected static ?int $navigationSort = 2;

    //label
    protected static ?string $navigationLabel = 'الخصائص';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('معرف الخاصية')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('معرف فريد للخاصية (مثل العنوان، السعر، اللون)'),

                        Forms\Components\TextInput::make('title')
                            ->label('العنوان')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('أدخل العنوان بالعربية'),

                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->maxLength(1000)
                            ->placeholder('أدخل الوصف بالعربية')
                            ->rows(3),

                        Forms\Components\Select::make('type')
                            ->label('نوع الخاصية')
                            ->required()
                            ->options([
                                'text' => 'نص (سطر واحد)',
                                'textarea' => 'نص متعدد الأسطر',
                                'number' => 'رقم',
                                'decimal' => 'رقم عشري',
                                'select' => 'قائمة منسدلة (اختيار واحد)',
                                'multiselect' => 'قائمة متعددة الاختيارات',
                                'checkbox' => 'صندوق اختيار',
                                'radio' => 'أزرار راديو',
                                'date' => 'تاريخ',
                                'datetime' => 'تاريخ ووقت',
                                'time' => 'وقت',
                                'file' => 'رفع ملف',
                                'image' => 'رفع صورة',
                                'url' => 'رابط',
                                'email' => 'بريد إلكتروني',
                                'phone' => 'رقم هاتف',
                                'color' => 'اختيار لون',
                                'range' => 'شريط نطاق',
                            ])
                            ->reactive()
                            ->helperText('اختر نوع الإدخال لهذه الخاصية'),

                        Forms\Components\Select::make('group')
                            ->label('مجموعة الخصائص')
                            ->options(PropertyGroup::pluck('title', 'name'))
                            ->searchable()
                            ->preload()
                            ->helperText('جمّع الخصائص معاً لتنظيم أفضل'),
                    ])->columns(2),

                Forms\Components\Section::make('القيم والخيارات')
                    ->schema([
                        // Options for select, multiselect, radio, checkbox
                        Forms\Components\KeyValue::make('options')
                            ->label('الخيارات')
                            ->keyLabel('القيمة')
                            ->valueLabel('التسمية')
                            ->addActionLabel('إضافة خيار')
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['select', 'multiselect', 'radio', 'checkbox']))
                            ->helperText('أضف خيارات للقوائم المنسدلة أو أزرار الراديو أو صناديق الاختيار'),

                        // Default value - different behavior based on type
                        Forms\Components\TextInput::make('default_value_text')
                            ->label('القيمة الافتراضية')
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['text', 'textarea', 'number', 'decimal', 'url', 'email', 'phone']))
                            ->helperText('القيمة الافتراضية لهذه الخاصية'),

                        Forms\Components\Select::make('default_value_select')
                            ->label('القيمة الافتراضية')
                            ->options(function (Forms\Get $get): array {
                                $options = $get('options');
                                if (!$options || !is_array($options)) {
                                    return [];
                                }

                                try {
                                    return collect($options)->pluck('value', 'key')->filter()->toArray();
                                } catch (\Exception $e) {
                                    return [];
                                }
                            })
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['select', 'radio']))
                            ->helperText('اختر القيمة الافتراضية من الخيارات المتاحة')
                            ->searchable(),

                        Forms\Components\CheckboxList::make('default_value_multiselect')
                            ->label('القيم الافتراضية')
                            ->options(function (Forms\Get $get): array {
                                $options = $get('options');
                                if (!$options || !is_array($options)) {
                                    return [];
                                }

                                try {
                                    return collect($options)->pluck('value', 'key')->filter()->toArray();
                                } catch (\Exception $e) {
                                    return [];
                                }
                            })
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['multiselect', 'checkbox']))
                            ->helperText('اختر القيم الافتراضية من الخيارات المتاحة'),

                        Forms\Components\Toggle::make('default_value_checkbox')
                            ->label('القيمة الافتراضية')
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'checkbox')
                            ->helperText('هل هذه الخاصية مفعلة افتراضياً؟'),

                        Forms\Components\DatePicker::make('default_value_date')
                            ->label('التاريخ الافتراضي')
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['date', 'datetime']))
                            ->helperText('التاريخ الافتراضي لهذه الخاصية'),

                        Forms\Components\TimePicker::make('default_value_time')
                            ->label('الوقت الافتراضي')
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'time')
                            ->helperText('الوقت الافتراضي لهذه الخاصية'),

                        Forms\Components\ColorPicker::make('default_value_color')
                            ->label('اللون الافتراضي')
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'color')
                            ->helperText('اللون الافتراضي لهذه الخاصية'),

                        // Unit field - only for numeric types
                        Forms\Components\TextInput::make('unit')
                            ->label('وحدة القياس')
                            ->maxLength(50)
                            ->placeholder('سم، كجم، دولار، إلخ')
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['number', 'decimal', 'range']))
                            ->helperText('وحدة للخصائص الرقمية'),

                        // Placeholder text - only for text-based types
                        Forms\Components\TextInput::make('placeholder')
                            ->label('نص النموذج')
                            ->maxLength(255)
                            ->placeholder('أدخل نص النموذج بالعربية')
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['text', 'textarea', 'number', 'decimal', 'url', 'email', 'phone']))
                            ->helperText('نص تلميحي يظهر في حقل الإدخال'),

                        // File upload specific options
                        Forms\Components\TextInput::make('file_types')
                            ->label('أنواع الملفات المسموحة')
                            ->placeholder('pdf,doc,docx,jpg,png')
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['file', 'image']))
                            ->helperText('أنواع الملفات المسموح رفعها (مفصولة بفواصل)'),

                        Forms\Components\TextInput::make('max_file_size')
                            ->label('الحد الأقصى لحجم الملف (ميجابايت)')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['file', 'image']))
                            ->helperText('الحد الأقصى لحجم الملف المسموح رفعه'),

                        // Range slider specific options
                        Forms\Components\TextInput::make('step')
                            ->label('خطوة النطاق')
                            ->numeric()
                            ->minValue(0.1)
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'range')
                            ->helperText('الخطوة بين القيم في شريط النطاق'),

                        // URL specific options
                        Forms\Components\TextInput::make('url_pattern')
                            ->label('نمط الرابط')
                            ->placeholder('https://example.com/*')
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'url')
                            ->helperText('نمط الرابط المسموح (اختياري)'),

                        // Email specific options
                        Forms\Components\Toggle::make('send_verification')
                            ->label('إرسال رسالة تأكيد')
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'email')
                            ->helperText('إرسال رسالة تأكيد عند إدخال البريد الإلكتروني'),

                        // Phone specific options
                        Forms\Components\Select::make('phone_format')
                            ->label('تنسيق الهاتف')
                            ->options([
                                'international' => 'دولي (+966)',
                                'local' => 'محلي (05xxxxxxxx)',
                                'custom' => 'مخصص',
                            ])
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'phone')
                            ->helperText('تنسيق رقم الهاتف المتوقع'),
                    ])->columns(2)
                    ->visible(fn(Forms\Get $get): bool => $get('type') !== null)
                    ->collapsible()
                    ->collapsed(fn(Forms\Get $get): bool => !in_array($get('type'), ['select', 'multiselect', 'radio', 'checkbox', 'file', 'image', 'range'])),

                Forms\Components\Section::make('Validation & Constraints')
                    ->schema([
                        Forms\Components\Toggle::make('is_required')
                            ->label('Required')
                            ->default(false)
                            ->helperText('Make this property mandatory'),

                        Forms\Components\TextInput::make('validation_rules')
                            ->label('Custom Validation Rules')
                            ->maxLength(500)
                            ->placeholder('min:0|max:100|numeric')
                            ->helperText('Laravel validation rules (optional)'),

                        Forms\Components\TextInput::make('min_length')
                            ->label('Minimum Length')
                            ->numeric()
                            ->minValue(0)
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['text', 'textarea'])),

                        Forms\Components\TextInput::make('max_length')
                            ->label('Maximum Length')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['text', 'textarea'])),

                        Forms\Components\TextInput::make('min_value')
                            ->label('Minimum Value')
                            ->numeric()
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['number', 'decimal', 'range'])),

                        Forms\Components\TextInput::make('max_value')
                            ->label('Maximum Value')
                            ->numeric()
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), ['number', 'decimal', 'range'])),

                        Forms\Components\TextInput::make('min_selections')
                            ->label('Minimum Selections')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'multiselect'),

                        Forms\Components\TextInput::make('max_selections')
                            ->label('Maximum Selections')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn(Forms\Get $get): bool => $get('type') === 'multiselect'),
                    ])->columns(2),

                Forms\Components\Section::make('Display & Behavior')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon')
                            ->maxLength(100)
                            ->placeholder('heroicon-o-tag'),

                        Forms\Components\ColorPicker::make('color')
                            ->label('Color')
                            ->helperText('Color for UI elements'),

                        Forms\Components\TextInput::make('display_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),

                        Forms\Components\Toggle::make('is_searchable')
                            ->label('Searchable')
                            ->default(false)
                            ->helperText('Include in search results'),

                        Forms\Components\Toggle::make('is_filterable')
                            ->label('Filterable')
                            ->default(false)
                            ->helperText('Allow filtering by this property'),

                        Forms\Components\Toggle::make('is_sortable')
                            ->label('Sortable')
                            ->default(false)
                            ->helperText('Allow sorting by this property'),

                        Forms\Components\Toggle::make('is_unique')
                            ->label('Unique')
                            ->default(false)
                            ->helperText('Ensure unique values across items'),

                        Forms\Components\Toggle::make('is_encrypted')
                            ->label('Encrypted')
                            ->default(false)
                            ->helperText('Encrypt stored values (for sensitive data)'),
                    ])->columns(2),

                Forms\Components\Section::make('Advanced Features')
                    ->schema([
                        Forms\Components\KeyValue::make('conditional_logic')
                            ->label('Conditional Logic')
                            ->keyLabel('Condition')
                            ->valueLabel('Action')
                            ->addActionLabel('Add Condition')
                            ->helperText('Define when to show/hide this property'),

                        Forms\Components\Textarea::make('help_text')
                            ->label('Help Text')
                            ->maxLength(500)
                            ->placeholder('Enter Arabic help text')
                            ->rows(2)
                            ->helperText('Additional guidance for users'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('المعرف')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('النوع')
                    ->colors([
                        'primary' => 'text',
                        'secondary' => 'textarea',
                        'success' => 'number',
                        'warning' => 'select',
                        'danger' => 'file',
                        'info' => 'date',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('group')
                    ->label('المجموعة')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_required')
                    ->label('مطلوب')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_searchable')
                    ->label('قابل للبحث')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_filterable')
                    ->label('قابل للتصفية')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('categories_count')
                    ->label('الفئات')
                    ->counts('categories')
                    ->sortable(),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('الترتيب')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('تصفية حسب النوع')
                    ->options([
                        'text' => 'نص',
                        'textarea' => 'نص متعدد الأسطر',
                        'number' => 'رقم',
                        'decimal' => 'رقم عشري',
                        'select' => 'قائمة منسدلة',
                        'multiselect' => 'قائمة متعددة الاختيارات',
                        'checkbox' => 'صندوق اختيار',
                        'radio' => 'أزرار راديو',
                        'date' => 'تاريخ',
                        'datetime' => 'تاريخ ووقت',
                        'time' => 'وقت',
                        'file' => 'ملف',
                        'image' => 'صورة',
                        'url' => 'رابط',
                        'email' => 'بريد إلكتروني',
                        'phone' => 'هاتف',
                        'color' => 'لون',
                        'range' => 'نطاق',
                    ]),

                Tables\Filters\SelectFilter::make('group')
                    ->label('تصفية حسب المجموعة')
                    ->options(PropertyGroup::pluck('title', 'name')),

                Tables\Filters\TernaryFilter::make('is_required')
                    ->label('حالة المطلوب'),

                Tables\Filters\TernaryFilter::make('is_searchable')
                    ->label('حالة قابلية البحث'),

                Tables\Filters\TernaryFilter::make('is_filterable')
                    ->label('حالة قابلية التصفية'),

                Tables\Filters\TernaryFilter::make('is_sortable')
                    ->label('حالة قابلية الترتيب'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('manage_categories')
                    ->label('الفئات')
                    ->icon('heroicon-o-rectangle-stack')
                    ->url(fn(Property $record): string => route('filament.admin.resources.properties.edit', $record) . '?activeTab=categories'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order');
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'view' => Pages\ViewProperty::route('/{record}'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('categories');
    }
}
