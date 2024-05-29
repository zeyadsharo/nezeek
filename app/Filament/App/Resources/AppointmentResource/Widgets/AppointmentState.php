<?php

namespace App\Filament\App\Resources\AppointmentResource\Widgets;

use App\Models\Appointment;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppointmentState extends BaseWidget
{
    protected static bool $isLazy = false;


    /**
     * Get the statistics for the widget.
     *
     * @return array
     */
    protected function getStats(): array
    {
        $numberOfRecord = getNumberOfModelRecords('appointments');
        return $numberOfRecord
            ? $this->getStatsWhenRecordsExist($numberOfRecord)
            : $this->getStatsWhenNoRecords();
    }

    /**
     * Get the statistics when there are no records.
     *
     * @return array
     */
    private function getStatsWhenNoRecords(): array
    {
        return [
            Stat::make('Total Posts', 0),
            Stat::make('Left Posts', 0),
            Stat::make('Created Posts', Appointment::count()),
        ];
    }

    /**
     * Get the statistics when records exist.
     *
     * @param int $numberOfRecord The number of records.
     * @return array
     */
    private function getStatsWhenRecordsExist($numberOfRecord): array
    {
        $postCount = Appointment::count();
        return [
            Stat::make('Total Appointments', formatNumber($numberOfRecord) )
                ->description('The total number of appointments.')
                ->descriptionIcon('heroicon-s-newspaper')
            ->color('success'),
            Stat::make('Left Appointments', formatNumber($numberOfRecord - $postCount))
            ->description('The number of Appointments left to create.')
            ->descriptionIcon('heroicon-s-newspaper')
            ->color('warning') ,
            Stat::make('Created Appointments', formatNumber($postCount) )
                ->description('The number of Appointments that have been created.')
                ->descriptionIcon('heroicon-s-newspaper')
                ->color('info'),
        ];
    }
}
