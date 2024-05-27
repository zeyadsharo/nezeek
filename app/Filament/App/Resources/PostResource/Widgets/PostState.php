<?php

namespace App\Filament\App\Resources\PostResource\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostState extends BaseWidget
{
    protected static bool $isLazy = false;


    /**
     * Get the statistics for the widget.
     *
     * @return array
     */
    protected function getStats(): array
    {
        $numberOfRecord = getNumberOfModelRecords('posts');
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
            Stat::make('Created Posts', Post::count()),
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
        $postCount = Post::count();
        return [
            Stat::make('Total Posts', formatNumber($numberOfRecord) )
                ->description('The total number of posts that can be created.')
                ->descriptionIcon('heroicon-s-newspaper')
            ->color('success'),
            Stat::make('Left Posts', formatNumber($numberOfRecord - $postCount))
            ->description('The number of posts left to create.')
            ->descriptionIcon('heroicon-s-newspaper')
            ->color('warning') ,
            Stat::make('Created Posts', formatNumber($postCount) )
                ->description('The number of posts that have been created.')
                ->descriptionIcon('heroicon-s-newspaper')
                ->color('info'),
        ];
    }
}
