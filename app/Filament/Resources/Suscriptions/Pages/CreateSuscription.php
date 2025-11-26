<?php

namespace App\Filament\Resources\Suscriptions\Pages;

use App\Filament\Resources\Suscriptions\SuscriptionResource;
use App\Mail\SuscriptionActivated;
use App\Models\Plan;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

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

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function afterCreate(){
        $suscription = $this->record;
        
        Notification::make()
            ->title("Suscripción {$suscription->number} creada correctamente!")
            ->success()
            ->send();

        $recipient = auth()->user();
        Notification::make()
            ->title('Nueva suscripción!')
            ->body("Fue creada la suscripcion No. {$suscription->number}")
            ->icon(Heroicon::CalendarDateRange)
            ->sendToDatabase($recipient);

        //envio de email 
        $customer_mail = "";
        if ($suscription->customer->email !== null) {
            if (filter_var($suscription->customer->email, FILTER_VALIDATE_EMAIL)) {
                $customer_mail = $suscription->customer->email;

                $email = Mail::to($customer_mail)->send(new SuscriptionActivated($suscription));
                if ($email) {
                    Notification::make()
                    ->title("Email enviado!")
                    ->success()
                    ->send();
                }else {
                    Notification::make()
                    ->title("Error al enviar email")
                    ->body('Hubo un error al enviar el email.')
                    ->error()
                    ->send();
                }
            }else {
                Notification::make()
                ->title("Error al enviar email")
                ->body('Email no válido o inexistente.')
                ->error()
                ->send();
            }
        }else {
                Notification::make()
                ->title("Error al enviar email")
                ->body('Email no válido o inexistente.')
                ->error()
                ->send();
            }
    }
}
