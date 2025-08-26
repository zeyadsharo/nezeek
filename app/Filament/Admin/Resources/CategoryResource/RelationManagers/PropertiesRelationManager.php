<?php

namespace App\Filament\Admin\Resources\CategoryResource\RelationManagers;

use App\Models\Property;
use App\Models\PropertyGroup;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;

class PropertiesRelationManager extends RelationManager
{
    protected static string $relationship = 'properties';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = 'Properties';

    public function form(Form $form): Form
    {
        return $form
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
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Property')
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Medium),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('pivot.custom_label')
                    ->label('Custom Label')
                    ->searchable()
                    ->placeholder('—')
                    ->size(TextColumnSize::Small),

                TextColumn::make('pivot.display_order')
                    ->label('Order')
                    ->sortable()
                    ->numeric(),

                IconColumn::make('pivot.is_visible')
                    ->label('Visible')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('pivot.is_editable')
                    ->label('Editable')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('pivot.is_required')
                    ->label('Required')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('pivot.custom_validation_rules')
                    ->label('Custom Rules')
                    ->placeholder('—')
                    ->size(TextColumnSize::Small)
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'number' => 'Number',
                        'select' => 'Select',
                        'checkbox' => 'Checkbox',
                        'date' => 'Date',
                        'file' => 'File',
                        'image' => 'Image',
                    ])
                    ->label('Property Type'),

                Tables\Filters\TernaryFilter::make('pivot.is_visible')
                    ->label('Visible Status'),

                Tables\Filters\TernaryFilter::make('pivot.is_required')
                    ->label('Required Status'),

                Tables\Filters\TernaryFilter::make('pivot.is_editable')
                    ->label('Editable Status'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('property_group_id')
                            ->label('Property Group')
                            ->options(PropertyGroup::pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('Select group (optional)'),
                        TextInput::make('custom_label')
                            ->label('Custom Label')
                            ->maxLength(255),
                        TextInput::make('display_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_visible')
                            ->label('Visible')
                            ->default(true),
                        Toggle::make('is_editable')
                            ->label('Editable')
                            ->default(true),
                        Toggle::make('is_required')
                            ->label('Required')
                            ->default(false),
                    ])
                    ->preloadRecordSelect()
                    ->label('Attach Property'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make()
                    ->form(fn(EditAction $action): array => [
                        TextInput::make('custom_label')
                            ->label('Custom Label')
                            ->maxLength(255),
                        Textarea::make('custom_help_text')
                            ->label('Custom Help Text')
                            ->maxLength(500)
                            ->rows(2),
                        TextInput::make('custom_validation_rules')
                            ->label('Custom Validation Rules')
                            ->maxLength(255),
                        TextInput::make('display_order')
                            ->label('Display Order')
                            ->numeric(),
                        Toggle::make('is_visible')
                            ->label('Visible'),
                        Toggle::make('is_editable')
                            ->label('Editable'),
                        Toggle::make('is_required')
                            ->label('Required'),
                        Textarea::make('custom_options')
                            ->label('Custom Options')
                            ->placeholder('{"option1": "value1", "option2": "value2"}')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('show_when')
                            ->label('Show When')
                            ->placeholder('{"property": "value"}')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('hide_when')
                            ->label('Hide When')
                            ->placeholder('{"property": "value"}')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                DetachAction::make()
                    ->label('Remove Property'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Remove Selected Properties'),
                ]),
            ]);
    }
}