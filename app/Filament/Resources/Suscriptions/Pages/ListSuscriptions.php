<?php

namespace App\Filament\Resources\Suscriptions\Pages;

use App\Filament\Resources\Suscriptions\SuscriptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuscriptions extends ListRecords
{
    protected static string $resource = SuscriptionResource::class;
    protected ?string $heading = 'Lista de Suscripciones';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
