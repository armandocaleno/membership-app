<?php

namespace App\Filament\Resources\Suscriptions\Pages;

use App\Filament\Resources\Incomes\IncomeResource;
use App\Filament\Resources\Suscriptions\SuscriptionResource;
use App\Models\Suscription;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSuscription extends ViewRecord
{
    protected static string $resource = SuscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('paid')
            ->label('Registrar Cobro')
            ->icon('heroicon-o-arrow-top-right-on-square')
            ->url(IncomeResource::getUrl('create', ['id' => $this->record->id, 'model' => 'suscription']))
            ->visible( $this->record->payment_status !== 'paid' && auth()->user()->can('Create:Income')),
        ];
    }
}
