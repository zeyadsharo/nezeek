<?php

namespace App\Filament\App\Widgets;

use App\Models\Appointment;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Appointment::class;

    public function fetchEvents(array $fetchInfo): array
    {

       //"start" => "2024-04-28T00:00:00Z"
  ///"end" => "2024-06-09T00:00:00Z"
       return $data= Appointment::query()
            ->where('start_date', '>=', $fetchInfo['start'])
            ->where('end_date', '<=', $fetchInfo['end'])
        ->get()
            ->map(function (Appointment $task) {
                return [
                    'id'    => $task->id,
                    'title' => $task->public_label,
                    'start' => $task->start_date,
                    'end'   => $task->end_date,
                    'color' => $task->color,
                ];
            })
            ->toArray();
    }

    public static function canView(): bool
    {
        return true;
    }
    public function getFormSchema(): array
    {
        return [
            TextInput::make('private_label')
                ->maxLength(40)
                ->required()
                ->rules(['string'])
                ->label(__('Private Label'))  // Translation for label
                ->placeholder(__('Enter private label')),  // Placeholder with translation

            TextInput::make('public_label')
                ->maxLength(255)
                ->required()
                ->rules(['string'])
                ->label(__('Public Label'))  // Translation for label
                ->placeholder(__('Enter public label')),  // Placeholder with translation

            DatePicker::make('start_date')
                ->required()
                ->label(__('Start Date'))  // Translation for label
                ->placeholder(__('Select start date'))  // Placeholder with translation
                ->default(now())  // Set today's date as the default for the start date
                ->rules(['date', 'after_or_equal:end_date']),  // Ensure start date is before or on the same day as end date

            DatePicker::make('end_date')
                ->required()
                ->label(__('End Date'))  // Translation for label
                ->placeholder(__('Select end date'))  // Placeholder with translation
                ->default(now()->addMonths(3))  // Set default to one week after today for the end date
                ->rules(['date', 'after_or_equal:start_date']),  // Ensure end date is after or on the same day as start date


         ColorPicker::make('color')
                ->label(__('Color'))  // Translation for label
                ->placeholder(__('Select a color')),  // Placeholder with translation

            Toggle::make('is_private')
                ->required()
                ->label(__('Is Private'))  // Translation for label
                ->inline(),
                
        ];
    }
}
