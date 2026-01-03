<?php

namespace App\Filament\Resources\Supports\Pages;

use App\Filament\Resources\Supports\SupportResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSupport extends EditRecord
{
    protected static string $resource = SupportResource::class;

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
                                ->body('No se puede eliminar este soporte porque tiene ingresos relacionados.')
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
