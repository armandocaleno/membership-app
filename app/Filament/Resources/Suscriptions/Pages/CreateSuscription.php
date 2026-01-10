<?php

namespace App\Filament\Resources\Suscriptions\Pages;

use App\Filament\Resources\Suscriptions\SuscriptionResource;
use App\Mail\SuscriptionActivated;
use App\Models\Plan;
use App\Models\Settings;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class CreateSuscription extends CreateRecord
{
    protected static string $resource = SuscriptionResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //crea el numero de la suscripcion a partir de la fecha y hora
        $data['number'] = Carbon::now()->format('Ymdhis');

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

        $send_email_suscription = (bool)Settings::where('name', 'send_email_notification')->value('value');

        //envio de email 
        if ($send_email_suscription) {
            $customer_mail = "";
            if ($suscription->customer->email !== null) {
                if (filter_var($suscription->customer->email, FILTER_VALIDATE_EMAIL)) {
                    $customer_mail = $suscription->customer->email;
                    $copy_recipient = Settings::where('name', 'mail_copy_recipient')->value('value');
                    $email = Mail::to($customer_mail)
                                ->bcc($copy_recipient)
                                ->queue(new SuscriptionActivated($suscription));
                    
                    if ($email) {
                        Notification::make()
                        ->title("Email enviado!")
                        ->success()
                        ->send();
                    }else {
                        Notification::make()
                        ->title("Error al enviar email")
                        ->body('Hubo un error al enviar el email.')
                        ->warning()
                        ->send();
                    }
                }else {
                    Notification::make()
                    ->title("Error al enviar email")
                    ->body('Email no válido o inexistente.')
                    ->warning()
                    ->send();
                }
            }else {
                    Notification::make()
                    ->title("Error al enviar email")
                    ->body('Email no válido o inexistente.')
                    ->warning()
                    ->send();
            }
        }
    }
}
