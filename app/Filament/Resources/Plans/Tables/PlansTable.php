<?php

namespace App\Filament\Resources\Plans\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nombre'),
                TextColumn::make('devices')
                    ->numeric()
                    ->label('Dispositivos')
                    ->alignCenter()
                    ->icon(Heroicon::ComputerDesktop),
                TextColumn::make('months')
                    ->numeric()
                    ->label('Meses')
                    ->alignCenter(),
                TextColumn::make('price')
                    ->money()
                    ->label('Precio')
                    ->icon(Heroicon::CurrencyDollar),
                TextColumn::make('status')
                    ->badge()
                    ->label('Estado')
                    ->alignCenter()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == 'active') {
                            return 'Activo';
                        }else {
                            return 'Inactivo';
                        }
                    }),
                TextColumn::make('products')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->label('Productos')
                    ->formatStateUsing(function ($state){
                        if (is_array($state)) {
                            implode(' -> ', $state);
                        }
                        return $state;
                    }),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, $record) {
                        if ($record->suscriptions()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este plan porque tiene suscripciones relacionadas.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }
                    })
            ]);
    }
}
