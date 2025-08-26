<?php

namespace App\Filament\Admin\Resources\PropertyGroupResource\Pages;

use App\Filament\Admin\Resources\PropertyGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPropertyGroup extends ViewRecord
{
    protected static string $resource = PropertyGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
