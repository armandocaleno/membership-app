<?php

namespace App\Filament\Resources\PaymentMethods\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentMethodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nombre')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                ->before(function (DeleteAction $action, $record) {
                        if ($record->incomes()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este método de pago porque tiene ingresos relacionados.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }
                    })
            ]);
    }
}
