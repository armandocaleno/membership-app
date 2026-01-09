<?php

namespace App\Filament\Resources\Suscriptions\Pages;

use App\Filament\Resources\Suscriptions\SuscriptionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSuscription extends ViewRecord
{
    protected static string $resource = SuscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
        ];
    }
}
