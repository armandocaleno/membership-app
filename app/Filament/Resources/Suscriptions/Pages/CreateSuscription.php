<?php

namespace App\Filament\Resources\Suscriptions\Pages;

use App\Filament\Resources\Suscriptions\SuscriptionResource;
use App\Models\Plan;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateSuscription extends CreateRecord
{
    protected static string $resource = SuscriptionResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //crea el numero de la suscripcion a partir de la fecha y hora
        $data['number'] = Carbon::now()->format('Ymdhi');

        // calcula la fecha en la que termina la suscripcion 
        // a partir de la fecha de incio y los meses del plan
        $start_date_input = $data['start_date'];

        //obtiene la cantidad de meses del plan seleccionado en el input
        $plan = Plan::findOrFail($data['plan_id']);
       
        $months = 0;
        if ($plan) {
            $months = $plan->months;
        }
        
        $start_date = Carbon::parse($start_date_input);
        $end_date = $start_date->addMonth($months);
        $data['end_date'] = $end_date->toDateString();

        return $data;
    }
}
