<?php

namespace App\Filament\Resources\Incomes\Pages;

use App\Filament\Resources\Incomes\IncomeResource;
use App\Models\Support;
use App\Models\Suscription;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateIncome extends CreateRecord
{
    protected static string $resource = IncomeResource::class;
    protected static bool $canCreateAnother = false;
    public $id, $model, $incomeable_resource;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $saldo = 0;
        $model = "";
        if ($this->model !== null) {
            if ($this->model == 'suscription') {
                $this->incomeable_resource = Suscription::class;
                $model = Suscription::findOrFail($this->id);
                $saldo = $model->saldo();
                if ($data['total'] > $saldo) {
                    Notification::make()
                    ->title('El monto es mayor al saldo.')
                    ->send();
                }
            }elseif ($this->model == 'support') {
                $this->incomeable_resource = Support::class;
            }
        }

        $data['incomeable_id'] = $this->id;
        $data['incomeable_type'] = $this->incomeable_resource;
        return $data;
    }

}
