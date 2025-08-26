<?php

namespace App\Filament\Admin\Resources\PropertyValueResource\Pages;

use App\Filament\Admin\Resources\PropertyValueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyValue extends EditRecord
{
    protected static string $resource = PropertyValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
