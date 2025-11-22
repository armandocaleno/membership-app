<?php

namespace App\Filament\Resources\Regimes\Pages;

use App\Filament\Resources\Regimes\RegimeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRegime extends CreateRecord
{
    protected static string $resource = RegimeResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
