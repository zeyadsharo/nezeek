<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropertyValueResource\Pages;
use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyValue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PropertyValueResource extends Resource
{
    protected static ?string $model = PropertyValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    //label
    protected static ?string $navigationLabel = 'قيم الخصائص';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('الفئة')
                            ->relationship('category', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('property_id')
                            ->label('الخاصية')
                            ->relationship('property', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('value', null)),

                        Forms\Components\TextInput::make('item_id')
                            ->label('معرف العنصر')
                            ->required()
                            ->numeric()
                            ->helperText('معرف العنصر الذي تنتمي إليه هذه القيمة'),

                        Forms\Components\TextInput::make('item_type')
                            ->label('نوع العنصر')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('App\Models\Product')
                            ->helperText('اسم الفئة الكامل لنموذج العنصر'),
                    ])->columns(2),

                Forms\Components\Section::make('تخزين القيمة')
                    ->schema([
                        Forms\Components\TextInput::make('value')
                            ->label('القيمة النصية')
                            ->maxLength(1000)
                            ->placeholder('أدخل القيمة النصية')
                            ->helperText('للنصوص البسيطة'),

                        Forms\Components\KeyValue::make('json_value')
                            ->label('قيمة JSON')
                            ->keyLabel('المفتاح')
                            ->valueLabel('القيمة')
                            ->addActionLabel('إضافة عنصر')
                            ->helperText('للقيّم المعقدة (مصفوفات، كائنات)'),

                        Forms\Components\TextInput::make('numeric_value')
                            ->label('القيمة الرقمية')
                            ->numeric()
                            ->step(0.000001)
                            ->helperText('للقيّم الرقمية'),

                        Forms\Components\Toggle::make('boolean_value')
                            ->label('القيمة المنطقية')
                            ->helperText('للقيم صحيح/خطأ'),

                        Forms\Components\DatePicker::make('date_value')
                            ->label('قيمة التاريخ')
                            ->helperText('لقيّم التاريخ'),

                        Forms\Components\DateTimePicker::make('datetime_value')
                            ->label('قيمة التاريخ والوقت')
                            ->helperText('لقيّم التاريخ والوقت'),
                    ])->columns(2),

                Forms\Components\Section::make('معلومات إضافية')
                    ->schema([
                        Forms\Components\TextInput::make('unit')
                            ->label('الوحدة')
                            ->maxLength(50)
                            ->placeholder('سم، كجم، دولار، إلخ')
                            ->helperText('وحدة القياس'),

                        Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات')
                            ->maxLength(1000)
                            ->rows(3)
                            ->placeholder('ملاحظات إضافية حول هذه القيمة'),

                        Forms\Components\TextInput::make('source')
                            ->label('المصدر')
                            ->maxLength(255)
                            ->placeholder('من أين جاءت هذه القيمة')
                            ->helperText('مصدر هذه القيمة'),

                        Forms\Components\Toggle::make('is_verified')
                            ->label('مؤكد')
                            ->default(false)
                            ->helperText('ما إذا كانت هذه القيمة مؤكدة'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.title')
                    ->label('الفئة')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('property.title')
                    ->label('الخاصية')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('item_id')
                    ->label('معرف العنصر')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('item_type')
                    ->label('نوع العنصر')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->copyable(),

                Tables\Columns\TextColumn::make('formatted_value')
                    ->label('القيمة')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit')
                    ->label('الوحدة')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_verified')
                    ->label('مؤكد')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('source')
                    ->label('المصدر')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تم الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'title')
                    ->label('تصفية حسب الفئة'),

                Tables\Filters\SelectFilter::make('property')
                    ->relationship('property', 'title')
                    ->label('تصفية حسب الخاصية'),

                Tables\Filters\SelectFilter::make('item_type')
                    ->label('تصفية حسب نوع العنصر')
                    ->options([
                        'App\Models\Product' => 'المنتج',
                        'App\Models\Service' => 'الخدمة',
                        'App\Models\Item' => 'العنصر',
                    ]),

                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('حالة التأكيد'),

                Tables\Filters\Filter::make('has_value')
                    ->label('لديها قيمة')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('value')
                        ->orWhereNotNull('json_value')
                        ->orWhereNotNull('numeric_value')
                        ->orWhereNotNull('boolean_value')
                        ->orWhereNotNull('date_value')
                        ->orWhereNotNull('datetime_value')),

                Tables\Filters\Filter::make('no_value')
                    ->label('ليس لديها قيمة')
                    ->query(fn(Builder $query): Builder => $query->whereNull('value')
                        ->whereNull('json_value')
                        ->whereNull('numeric_value')
                        ->whereNull('boolean_value')
                        ->whereNull('date_value')
                        ->whereNull('datetime_value')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('verify')
                    ->label('تأكيد')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (PropertyValue $record) {
                        $record->update(['is_verified' => true]);
                    })
                    ->visible(fn(PropertyValue $record): bool => !$record->is_verified),

                Tables\Actions\Action::make('unverify')
                    ->label('إلغاء التأكيد')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (PropertyValue $record) {
                        $record->update(['is_verified' => false]);
                    })
                    ->visible(fn(PropertyValue $record): bool => $record->is_verified),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),

                Tables\Actions\BulkAction::make('verify_selected')
                    ->label('تأكيد المحدد')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            $record->update(['is_verified' => true]);
                        });
                    }),

                Tables\Actions\BulkAction::make('unverify_selected')
                    ->label('إلغاء التأكيد للمحدد')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            $record->update(['is_verified' => false]);
                        });
                    }),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListPropertyValues::route('/'),
            'create' => Pages\CreatePropertyValue::route('/create'),
            'view' => Pages\ViewPropertyValue::route('/{record}'),
            'edit' => Pages\EditPropertyValue::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['category', 'property']);
    }
}
