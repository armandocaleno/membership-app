<?php

namespace App\Filament\Resources\Incomes\Pages;

use App\Filament\Resources\Incomes\IncomeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditIncome extends EditRecord
{
    protected static string $resource = IncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // if ($this->model !== null) {
        //     if ($this->model == 'suscription') {
        //         $suscription = Suscription::findOrFail($this->id);
        //         $data['total'] = $suscription->plan->price;
        //         $data['date'] = '2000/10/10';
        //         // dd($data);
        //     }
        // }
        // $data['date'] = '2000/10/10';
        // dd($data);  
        return $data;
    }
}
