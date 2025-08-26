<?php

namespace App\Filament\Admin\Resources\PropertyGroupResource\Pages;

use App\Filament\Admin\Resources\PropertyGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyGroup extends EditRecord
{
    protected static string $resource = PropertyGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
