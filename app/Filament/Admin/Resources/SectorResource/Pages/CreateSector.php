<?php

namespace App\Filament\Admin\Resources\SectorResource\Pages;

use App\Filament\Admin\Resources\SectorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSector extends CreateRecord
{
    protected static string $resource = SectorResource::class;
    protected static bool $canCreateAnother = false;
    public  function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
