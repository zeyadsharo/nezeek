<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static $order = 1;
    protected function getStats(): array
    {
        return [
            // Stats will be added here as needed
        ];
    }
}
