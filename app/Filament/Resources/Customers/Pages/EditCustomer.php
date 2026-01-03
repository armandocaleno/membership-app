<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
            ->before(function (DeleteAction $action, $record) {
                        if ($record->suscriptions()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este cliente porque tiene suscripciones relacionadas.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }

                        if ($record->supports()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este cliente porque tiene soportes relacionadas.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }

                        if ($record->establishments()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este cliente porque tiene establecimientos relacionadas.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }
                    }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
