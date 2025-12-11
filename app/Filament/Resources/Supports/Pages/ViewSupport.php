<?php

namespace App\Filament\Resources\Supports\Pages;

use App\Filament\Resources\Incomes\IncomeResource;
use App\Filament\Resources\Supports\SupportResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSupport extends ViewRecord
{
    protected static string $resource = SupportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('paid')
            ->label('Registrar cobro')
            ->icon('heroicon-o-arrow-top-right-on-square')
            ->url(IncomeResource::getUrl('create', ['id' => $this->record->id, 'model' => 'support']))
            ->visible( $this->record->payment_status !== 'paid' ),
        ];
    }
}
