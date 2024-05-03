<?php

namespace App\Filament\Admin\Resources\DepartmentCategoryResource\Pages;

use App\Filament\Admin\Resources\DepartmentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepartmentCategories extends ListRecords
{
    protected static string $resource = DepartmentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
