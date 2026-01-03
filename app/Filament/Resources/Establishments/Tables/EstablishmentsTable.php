<?php

namespace App\Filament\Resources\Establishments\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class EstablishmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->label('Código'),
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nombre'),
                TextColumn::make('address')
                    ->searchable()
                    ->label('Dirección'),
                TextColumn::make('province')
                    ->label('Provincia')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('city')
                    ->label('Ciudad')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('Teléfono'),
                TextColumn::make('status')
                    ->badge()
                    ->label('Estado'),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->label('Cliente'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Creado'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Modificado'),
            ])
            ->recordActions([
                EditAction::make(),
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
                    })
            ])
            ->groups([
                Group::make('customer.name')
                ->label('Cliente'),
            ]);
    }
}
