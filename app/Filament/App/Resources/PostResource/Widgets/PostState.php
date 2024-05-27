<?php

namespace App\Filament\App\Resources\PostResource\Widgets;

use App\Models\Customer;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostState extends BaseWidget
{
    protected function getStats(): array
    {

        $numberOfRecordHas
            = \Illuminate\Support\Facades\DB::table('customers')
            ->leftJoin('subscriptions', 'customers.id', '=', 'subscriptions.customer_id')
            ->leftJoin('features', 'subscriptions.feature_id', '=', 'features.id')
            ->where('customers.admin_id', auth()->id())
            ->where('key', 'posts')
            ->select('features.key', 'subscriptions.number_of_records', 'customers.id as customer_id')
            ->get()
            ->first();

        // checkif no record found
        if (!$numberOfRecordHas) {
            return [
                Stat::make('Total Posts', 0),
                Stat::make('Confirmed Posts', 0),
                Stat::make('Cancelled Posts', 0),
            ];
        }else
        {
            return [
                Stat::make('Total Posts', $numberOfRecordHas->number_of_records),
                Stat::make('Left Posts', $numberOfRecordHas->number_of_records - Post::count()),
                Stat::make('Created Posts', Post::count()),
            ];
        }
    }
}
