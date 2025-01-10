<?php

namespace App\Filament\Admin\Resources\CustomerResource\Pages;

use App\Filament\Admin\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
    protected static bool $canCreateAnother = false;

    //disable the default create button

    protected function getHeaderActions(): array
    {
        return []; // This ensures no additional actions are shown in the header
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
