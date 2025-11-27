<?php

namespace App\Filament\Resources\Incomes\Pages;

use App\Filament\Resources\Incomes\IncomeResource;
use App\Models\Support;
use App\Models\Suscription;
use Filament\Resources\Pages\CreateRecord;

class CreateIncome extends CreateRecord
{
    protected static string $resource = IncomeResource::class;
    protected static bool $canCreateAnother = false;
    public $id, $model, $incomeable_resource;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($this->model !== null) {
            if ($this->model == 'suscription') {
                $this->incomeable_resource = Suscription::class;
            }elseif ($this->model == 'support') {
                $this->incomeable_resource = Support::class;
            }
        }

        $data['incomeable_id'] = $this->id;
        $data['incomeable_type'] = $this->incomeable_resource;
        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->model !== null) {
            if ($this->model == 'suscription') {
                $suscription = Suscription::findOrFail($this->id);
                $data['total'] = $suscription->plan->price;
            }
        }

        return $data;
    }

}
