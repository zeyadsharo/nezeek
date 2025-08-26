<?php

namespace App\Filament\Admin\Resources\PropertyValueResource\Pages;

use App\Filament\Admin\Resources\PropertyValueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyValues extends ListRecords
{
    protected static string $resource = PropertyValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
