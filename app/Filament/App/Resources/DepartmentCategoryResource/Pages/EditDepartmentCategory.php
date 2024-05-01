<?php

namespace App\Filament\App\Resources\DepartmentCategoryResource\Pages;

use App\Filament\App\Resources\DepartmentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepartmentCategory extends EditRecord
{
    protected static string $resource = DepartmentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
