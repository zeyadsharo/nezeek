<?php

namespace App\Filament\App\Widgets;

use App\Models\Appointment;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Appointment::class;
    //change title
    public string $title = 'Appointments';

    public function fetchEvents(array $fetchInfo): array
    {
        return $data = Appointment::query()
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
    public function config(): array
    {
        return [
            'firstDay' => 1, 'eventDisplay' => 'block',
            'headerToolbar' => [
                'left' => 'dayGridMonth,timeGridWeek,timeGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
                // render a solid rectangle

            ],
        ];
    }
    public static function canView(): bool
    {
        return true;
    }

    protected function modalActions(): array
    {
        return [
            EditAction::make()
                ->mountUsing(
                    function (Appointment $record, Form $form, array $arguments) {
                        //check arguments if not null
                        if ($arguments != null) {
                            $type = $arguments['type']; // drop or click
                            if ($type == 'drop') {
                                $form->fill([
                                    'start_date' => $arguments['event']['start'],
                                    'end_date' => $arguments['event']['start'],
                                    'color' =>  $arguments['event']['backgroundColor'],
                                    'public_label' => $record->public_label,
                                    'private_label' => $record->private_label,
                                ]);
                            }
                        }
                        else
                        {
                            $form->fill([
                                'start_date' => $record->start_date,
                                'end_date' => $record->end_date,
                                'color' => $record->color,
                                'public_label' => $record->public_label,
                                'private_label' => $record->private_label,
                            ]);
                        
                        }
                    }
                ),
            DeleteAction::make(),
        ];
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->modal()
                ->label(__('Create Appointment'))
                ->mountUsing(
                    function (Form $form, array $arguments) {
                        $form->fill([
                            'start_date' => $arguments['start'] ?? null,
                            'end_date' => $arguments['end'] ?? null,
                            'allDay' => true,
                            'color' => fake()->hexColor,

                        ]);
                    }
                )
        ];
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

            DateTimePicker::make('start_date')
                ->required()
                ->label(__('Start Date'))  // Translation for label
                ->placeholder(__('Select start date'))  // Placeholder with translation
                ->default(now())  // Set default to today for the start date
                ->rules(['date', 'after_or_equal:end_date']),  // Ensure start date is before or on the same day as end date

            DateTimePicker::make('end_date')
                ->required()
                ->label(__('End Date'))  // Translation for label
                ->placeholder(__('Select end date'))  // Placeholder with translation
                ->default(now())  // Set default to one week after today for the end date
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
    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event.title+"', start: '"+event.start+"', end: '"+event.end+"' }");
        }
    JS;
    }
}
