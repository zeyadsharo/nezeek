<?php

namespace App\Filament\App\Widgets;

use App\Models\Appointment;
use App\Models\Post;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
protected static $order = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Appointment Count', Appointment::count())
            ->description('Total appointments')
            ->descriptionIcon('heroicon-m-calendar')
            ->color('success'),
   //Post count
            Stat::make('Post Count', Post::count())
            ->description('Total posts')
            ->descriptionIcon('heroicon-s-newspaper')
            ->color('info'),

            // products count
            Stat::make('Product Count', Product::count())
            ->description('Total products')
            ->descriptionIcon('heroicon-s-shopping-cart')
            ->color('warning'),
            

        ];
    }
}
