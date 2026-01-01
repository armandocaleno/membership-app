<?php

namespace App\Filament\Resources\Supports\Pages;

use App\Filament\Resources\Supports\SupportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSupport extends CreateRecord
{
    protected static string $resource = SupportResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //crea el numero de soporte a partir de la fecha y hora
        $data['number'] = \Carbon\Carbon::now()->format('Ymdhis');

        return $data;
    }
}
