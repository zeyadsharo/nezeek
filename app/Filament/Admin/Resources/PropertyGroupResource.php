<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropertyGroupResource\Pages;
use App\Models\Property;
use App\Models\PropertyGroup;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PropertyGroupResource extends Resource
{
    protected static ?string $model = PropertyGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    protected static ?int $navigationSort = 3;

    //label
    protected static ?string $navigationLabel = 'مجموعات الخصائص';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('إدارة مجموعات الخصائص')
                    ->tabs([
                        Tabs\Tab::make('المعلومات الأساسية')
                            ->schema([
                                Forms\Components\Section::make('المعلومات الأساسية')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('معرف المجموعة')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->helperText('معرف فريد للمجموعة (مثال: basic_info, specifications)'),

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
                                            ->placeholder('heroicon-o-information-circle'),

                                        Forms\Components\ColorPicker::make('color')
                                            ->label('اللون')
                                            ->helperText('لون لعناصر الواجهة'),

                                        Forms\Components\Toggle::make('is_collapsible')
                                            ->label('قابل للطي')
                                            ->default(true)
                                            ->helperText('السماح للمستخدمين بطي هذه المجموعة'),

                                        Forms\Components\Toggle::make('is_expanded_by_default')
                                            ->label('موسع افتراضياً')
                                            ->default(false)
                                            ->helperText('إظهار هذه المجموعة موسعة في البداية'),

                                        Forms\Components\Toggle::make('is_active')
                                            ->label('نشط')
                                            ->default(true)
                                            ->helperText('إظهار هذه المجموعة للمستخدمين'),
                                    ])->columns(2),
                            ]),

                        Tabs\Tab::make('الخصائص')
                            ->schema([
                                Forms\Components\Section::make('إدارة الخصائص')
                                    ->description('إرفاق الخصائص بهذه المجموعة وتنظيمها')
                                    ->schema([
                                        Forms\Components\Repeater::make('group_properties')
                                            ->label('خصائص المجموعة')
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
                                                            }
                                                        }
                                                    }),

                                                Forms\Components\TextInput::make('custom_label')
                                                    ->label('التسمية المخصصة')
                                                    ->maxLength(255)
                                                    ->helperText('تجاوز التسمية الافتراضية للخاصية في هذه المجموعة'),

                                                Forms\Components\Textarea::make('custom_help_text')
                                                    ->label('نص المساعدة المخصص')
                                                    ->maxLength(500)
                                                    ->rows(2)
                                                    ->helperText('تجاوز نص المساعدة الافتراضي في هذه المجموعة'),

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
                                            ->description('إرفاق عدة خصائص بهذه المجموعة بسرعة')
                                            ->schema([
                                                Forms\Components\Select::make('quick_properties')
                                                    ->label('اختر الخصائص')
                                                    ->multiple()
                                                    ->options(function (?PropertyGroup $record) {
                                                        if (!$record) {
                                                            return Property::pluck('title', 'id');
                                                        }
                                                        // Get properties that are not already assigned to this group
                                                        $assignedPropertyIds = $record->properties()->pluck('properties.id')->toArray();
                                                        return Property::whereNotIn('id', $assignedPropertyIds)
                                                            ->pluck('title', 'id');
                                                    })
                                                    ->searchable()
                                                    ->preload()
                                                    ->placeholder('اختر الخصائص للإضافة')
                                                    ->helperText('يظهر فقط الخصائص غير المرفقة بالمجموعة'),

                                                Forms\Components\Toggle::make('make_required')
                                                    ->label('جعل مطلوب')
                                                    ->default(false)
                                                    ->helperText('جعل جميع الخصائص المحددة إلزامية'),

                                                Forms\Components\Toggle::make('make_visible')
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

                Tables\Columns\TextColumn::make('description')
                    ->label('الوصف')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('properties_count')
                    ->label('الخصائص')
                    ->counts('properties')
                    ->sortable(),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('الترتيب')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_collapsible')
                    ->label('قابل للطي')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_expanded_by_default')
                    ->label('موسع')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('حالة النشاط'),

                Tables\Filters\TernaryFilter::make('is_collapsible')
                    ->label('حالة القابلية للطي'),

                Tables\Filters\TernaryFilter::make('is_expanded_by_default')
                    ->label('موسع افتراضياً'),

                Tables\Filters\Filter::make('has_properties')
                    ->label('لها خصائص')
                    ->query(fn(Builder $query): Builder => $query->whereHas('properties')),

                Tables\Filters\Filter::make('no_properties')
                    ->label('مجموعات فارغة')
                    ->query(fn(Builder $query): Builder => $query->whereDoesntHave('properties')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPropertyGroups::route('/'),
            'create' => Pages\CreatePropertyGroup::route('/create'),
            'view' => Pages\ViewPropertyGroup::route('/{record}'),
            'edit' => Pages\EditPropertyGroup::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('properties');
    }
}
