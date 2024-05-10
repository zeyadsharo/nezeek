<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AppointmentResource\Pages;
use App\Filament\App\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('private_label')
            ->maxLength(40)
            ->required()
            ->rules(['string'])
                ->label(__('Private Label'))  // Translation for label
                ->placeholder(__('Enter private label')),  // Placeholder with translation

            Forms\Components\TextInput::make('public_label')
            ->maxLength(255)
            ->required()
            ->rules(['string'])
                ->label(__('Public Label'))  // Translation for label
                ->placeholder(__('Enter public label')),  // Placeholder with translation

            Forms\Components\DatePicker::make('start_date')
            ->required()
                ->label(__('Start Date'))  // Translation for label
                ->placeholder(__('Select start date'))  // Placeholder with translation
                ->default(now())  // Set today's date as the default for the start date
                ->rules(['date', 'after_or_equal:end_date']),  // Ensure start date is before or on the same day as end date

            Forms\Components\DatePicker::make('end_date')
            ->required()
                ->label(__('End Date'))  // Translation for label
                ->placeholder(__('Select end date'))  // Placeholder with translation
                ->default(now()->addMonths(3))  // Set default to one week after today for the end date
                ->rules(['date', 'after_or_equal:start_date']),  // Ensure end date is after or on the same day as start date

            Forms\Components\DatePicker::make('auto_delete_at')
            ->label(__('Auto Delete At'))  // Translation for label
            ->placeholder(__('Select auto delete date'))  // Placeholder with translation
            ->default(now()->addMonths(3))  // Set default to one month after today for the auto delete date
                ->rules(['date', 'after:end_date']),  // Optional rule to ensure auto delete date is after end date

            Forms\Components\ColorPicker::make('color')
            ->label(__('Color'))  // Translation for label
            ->placeholder(__('Select a color')),  // Placeholder with translation

            Forms\Components\Toggle::make('is_private')
            ->required()
                ->label(__('Is Private'))  // Translation for label
                ->inline(),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('private_label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('public_label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('auto_delete_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_private')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
