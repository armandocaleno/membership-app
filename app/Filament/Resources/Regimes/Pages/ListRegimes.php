<?php

namespace App\Filament\Resources\Regimes\Pages;

use App\Filament\Resources\Regimes\RegimeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegimes extends ListRecords
{
    protected static string $resource = RegimeResource::class;

    protected ?string $heading = 'Lista de regímenes';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->createAnother(false),
        ];
    }
}
