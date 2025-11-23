<?php

namespace App\Filament\Resources\Plans\Pages;

use App\Filament\Resources\Plans\PlanResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPlan extends ViewRecord
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('back')
                ->label('Regresar')
                ->url(PlanResource::getUrl('index'))
                ->icon('heroicon-o-arrow-left')
        ];
    }
}
