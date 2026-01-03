<?php

namespace App\Filament\Resources\Establishments\Pages;

use App\Filament\Resources\Establishments\EstablishmentResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditEstablishment extends EditRecord
{
    protected static string $resource = EstablishmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
            ->before(function (DeleteAction $action, $record) {
                        if ($record->devices()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este establecimiento porque tiene dispositivos relacionadas.')
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
