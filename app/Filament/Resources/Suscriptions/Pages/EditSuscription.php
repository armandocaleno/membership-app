<?php

namespace App\Filament\Resources\Suscriptions\Pages;

use App\Models\Plan;
use Illuminate\Support\Carbon;
use App\Filament\Resources\Suscriptions\SuscriptionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSuscription extends EditRecord
{
    protected static string $resource = SuscriptionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
            ->before(function (DeleteAction $action, $record) {
                        if ($record->incomes()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este suscripción porque tiene ingresos relacionados.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }
                    })
            ->successNotification(Notification::make()
                                    ->title('Suscripcion eliminada!')
                                    ->body('La suscripción se eliminó correctamente!')
                                    ->success()),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
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

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    protected function afterSave():void
    {
        Notification::make()
            ->title('Suscripción editada correctamente!')
            ->success()
            ->send();
    }
}
