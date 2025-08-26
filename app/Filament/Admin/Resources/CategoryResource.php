<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyGroup;
use App\Models\Sector;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    //label
    protected static ?string $navigationLabel = 'الفئات';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('إدارة الفئات')
                    ->tabs([
                        Tabs\Tab::make('المعلومات الأساسية')
                            ->schema([
                                Forms\Components\Section::make('المعلومات الأساسية')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('المعرف الفريد')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->helperText('معرف فريد للفئة (مثال: إلكترونيات، هواتف)'),

                                        Forms\Components\TextInput::make('title')
                                            ->label('العنوان')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('أدخل العنوان باللغة العربية'),

                                        Forms\Components\Textarea::make('description')
                                            ->label('الوصف')
                                            ->maxLength(1000)
                                            ->placeholder('أدخل الوصف باللغة العربية')
                                            ->rows(3),

                                        Forms\Components\Select::make('sector_id')
                                            ->label('القطاع')
                                            ->relationship('sector', 'title')
                                            ->required()
                                            ->searchable()
                                            ->preload(),

                                        Forms\Components\Select::make('parent_id')
                                            ->label('الفئة الأب')
                                            ->relationship('parent', 'title')
                                            ->searchable()
                                            ->preload()
                                            ->placeholder('اختر الفئة الأب (اختياري)'),
                                    ])->columns(2),

                                Forms\Components\Section::make('العرض والتنظيم')
                                    ->schema([
                                        Forms\Components\TextInput::make('display_order')
                                            ->label('ترتيب العرض')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('الأرقام الأقل تظهر أولاً'),

                                        Forms\Components\TextInput::make('icon')
                                            ->label('الأيقونة')
                                            ->maxLength(100)
                                            ->placeholder('heroicon-o-device-phone-mobile'),

                                        Forms\Components\FileUpload::make('image')
                                            ->label('الصورة')
                                            ->image()
                                            ->directory('categories')
                                            ->maxSize(2048),

                                        Forms\Components\ColorPicker::make('color')
                                            ->label('اللون')
                                            ->helperText('لون لعناصر الواجهة'),
                                    ])->columns(2),

                                Forms\Components\Section::make('الحالة وتحسين محركات البحث')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('نشط')
                                            ->default(true)
                                            ->helperText('إظهار هذه الفئة للمستخدمين'),

                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('مميز')
                                            ->default(false)
                                            ->helperText('إبراز هذه الفئة'),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('رابط SEO')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->helperText('نسخة صديقة للرابط من العنوان'),

                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('عنوان Meta')
                                            ->maxLength(255)
                                            ->helperText('عنوان meta لتحسين محركات البحث'),

                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('وصف Meta')
                                            ->maxLength(500)
                                            ->rows(2)
                                            ->helperText('وصف meta لتحسين محركات البحث'),

                                        Forms\Components\TextInput::make('meta_keywords')
                                            ->label('كلمات مفتاحية Meta')
                                            ->maxLength(255)
                                            ->helperText('كلمات مفتاحية meta (مفصولة بفواصل)'),
                                    ])->columns(2),
                            ]),

                        Tabs\Tab::make('الخصائص')
                            ->schema([
                                Forms\Components\Section::make('إدارة الخصائص')
                                    ->description('إرفاق الخصائص بهذه الفئة وتنظيمها في مجموعات')
                                    ->schema([
                                        Forms\Components\Repeater::make('category_properties')
                                            ->label('خصائص الفئة')
                                            ->relationship('properties')
                                            ->schema([
                                                Forms\Components\Select::make('property_id')
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
                                                                $set('custom_validation_rules', $property->validation_rules);
                                                            }
                                                        }
                                                    }),

                                                Forms\Components\Select::make('property_group_id')
                                                    ->label('مجموعة الخصائص')
                                                    ->options(PropertyGroup::pluck('title', 'id'))
                                                    ->searchable()
                                                    ->preload()
                                                    ->placeholder('اختر المجموعة (اختياري)'),

                                                Forms\Components\TextInput::make('custom_label')
                                                    ->label('التسمية المخصصة')
                                                    ->maxLength(255)
                                                    ->helperText('تجاوز التسمية الافتراضية للخاصية في هذه الفئة'),

                                                Forms\Components\Textarea::make('custom_help_text')
                                                    ->label('نص المساعدة المخصص')
                                                    ->maxLength(500)
                                                    ->rows(2)
                                                    ->helperText('تجاوز نص المساعدة الافتراضي في هذه الفئة'),

                                                Forms\Components\TextInput::make('custom_validation_rules')
                                                    ->label('قواعد التحقق المخصصة')
                                                    ->maxLength(255)
                                                    ->helperText('تجاوز قواعد التحقق الافتراضية في هذه الفئة'),

                                                Forms\Components\TextInput::make('display_order')
                                                    ->label('ترتيب العرض')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->helperText('الترتيب داخل المجموعة'),

                                                Forms\Components\Toggle::make('is_visible')
                                                    ->label('مرئي')
                                                    ->default(true)
                                                    ->helperText('إظهار هذه الخاصية للمستخدمين'),

                                                Forms\Components\Toggle::make('is_editable')
                                                    ->label('قابل للتعديل')
                                                    ->default(true)
                                                    ->helperText('السماح للمستخدمين بتعديل هذه الخاصية'),

                                                Forms\Components\Toggle::make('is_required')
                                                    ->label('مطلوب')
                                                    ->default(false)
                                                    ->helperText('جعل هذه الخاصية إلزامية'),

                                                Forms\Components\Textarea::make('custom_options')
                                                    ->label('الخيارات المخصصة')
                                                    ->helperText('تجاوز خيارات الخاصية لهذه الفئة (تنسيق JSON)')
                                                    ->placeholder('{"option1": "value1", "option2": "value2"}')
                                                    ->rows(3)
                                                    ->columnSpanFull(),

                                                Forms\Components\Textarea::make('show_when')
                                                    ->label('إظهار عند')
                                                    ->helperText('المنطق الشرطي لإظهار هذه الخاصية (تنسيق JSON)')
                                                    ->placeholder('{"property": "value"}')
                                                    ->rows(3)
                                                    ->columnSpanFull(),

                                                Forms\Components\Textarea::make('hide_when')
                                                    ->label('إخفاء عند')
                                                    ->helperText('المنطق الشرطي لإخفاء هذه الخاصية (تنسيق JSON)')
                                                    ->placeholder('{"property": "value"}')
                                                    ->rows(3)
                                                    ->columnSpanFull(),
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

                                        Forms\Components\Section::make('التعيين السريع للخصائص')
                                            ->description('إرفاق عدة خصائص بهذه الفئة بسرعة')
                                            ->schema([
                                                Forms\Components\Select::make('quick_properties')
                                                    ->label('اختر الخصائص')
                                                    ->multiple()
                                                    ->options(Property::pluck('title', 'id'))
                                                    ->searchable()
                                                    ->preload()
                                                    ->placeholder('اختر الخصائص للإضافة')
                                                    ->helperText('اختر عدة خصائص لإضافتها بسرعة لهذه الفئة'),

                                                Forms\Components\Select::make('default_property_group')
                                                    ->label('المجموعة الافتراضية')
                                                    ->options(PropertyGroup::pluck('title', 'id'))
                                                    ->searchable()
                                                    ->preload()
                                                    ->placeholder('اختر المجموعة الافتراضية للتعيينات السريعة')
                                                    ->helperText('سيتم تعيين الخصائص لهذه المجموعة افتراضياً'),
                                            ])->columns(2),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
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

                Tables\Columns\TextColumn::make('sector.title')
                    ->label('القطاع')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.title')
                    ->label('الفئة الأب')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('children_count')
                    ->label('الفئات الفرعية')
                    ->counts('children')
                    ->sortable(),

                Tables\Columns\TextColumn::make('properties_count')
                    ->label('الخصائص')
                    ->counts('properties')
                    ->sortable(),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('الترتيب')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sector')
                    ->relationship('sector', 'title')
                    ->label('تصفية حسب القطاع'),

                Tables\Filters\SelectFilter::make('parent')
                    ->relationship('parent', 'title')
                    ->label('تصفية حسب الفئة الأب')
                    ->placeholder('جميع الفئات'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('حالة النشاط'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('حالة التمييز'),

                Tables\Filters\Filter::make('has_children')
                    ->label('لها فئات فرعية')
                    ->query(fn(Builder $query): Builder => $query->whereHas('children')),

                Tables\Filters\Filter::make('no_parent')
                    ->label('الفئات الجذرية')
                    ->query(fn(Builder $query): Builder => $query->whereNull('parent_id')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('manage_properties')
                    ->label('إدارة الخصائص')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('warning')
                    ->url(fn(Category $record): string => route('filament.admin.resources.categories.manage-properties', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('quick_assign_properties')
                    ->label('تعيين سريع')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('properties')
                            ->label('اختر الخصائص')
                            ->multiple()
                            ->options(function (Category $record) {
                                // Get properties that are not already assigned to this category
                                $assignedPropertyIds = $record->properties()->pluck('properties.id')->toArray();
                                return Property::whereNotIn('id', $assignedPropertyIds)
                                    ->pluck('title', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('اختر الخصائص للإضافة')
                            ->helperText('يظهر فقط الخصائص غير المرفقة بهذه الفئة'),

                        Forms\Components\Select::make('property_group_id')
                            ->label('مجموعة الخصائص')
                            ->options(PropertyGroup::pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('اختر المجموعة (اختياري)'),

                        Forms\Components\Toggle::make('is_required')
                            ->label('جعل مطلوب')
                            ->default(false)
                            ->helperText('جعل جميع الخصائص المحددة إلزامية'),

                        Forms\Components\Toggle::make('is_visible')
                            ->label('جعل مرئي')
                            ->default(true)
                            ->helperText('جعل جميع الخصائص المحددة مرئية'),
                    ])
                    ->action(function (array $data, Category $record): void {
                        if (!empty($data['properties'])) {
                            $properties = [];
                            foreach ($data['properties'] as $propertyId) {
                                $properties[$propertyId] = [
                                    'property_group_id' => $data['property_group_id'] ?? null,
                                    'display_order' => 0,
                                    'is_visible' => $data['is_visible'] ?? true,
                                    'is_editable' => true,
                                    'is_required' => $data['is_required'] ?? false,
                                    'custom_label' => null,
                                    'custom_help_text' => null,
                                    'custom_validation_rules' => null,
                                    'custom_options' => null,
                                    'show_when' => null,
                                    'hide_when' => null,
                                ];
                            }

                            // Use syncWithoutDetaching to avoid duplicate key errors
                            $record->properties()->syncWithoutDetaching($properties);

                            Notification::make()
                                ->title('تم تعيين الخصائص بنجاح')
                                ->success()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('تعيين سريع للخصائص')
                    ->modalDescription('اختر الخصائص لتعيينها بسرعة لهذه الفئة.')
                    ->modalSubmitActionLabel('تعيين الخصائص'),
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
            \App\Filament\Admin\Resources\CategoryResource\RelationManagers\PropertiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
            'manage-properties' => Pages\ManageCategoryProperties::route('/{record}/properties'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount(['children', 'properties']);
    }
}
