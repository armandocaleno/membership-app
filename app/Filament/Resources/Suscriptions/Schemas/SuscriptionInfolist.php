<?php

namespace App\Filament\Resources\Suscriptions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SuscriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('number')
                    ->label('Número'),
                TextEntry::make('start_date')
                    ->date()
                    ->label('Inicia'),
                TextEntry::make('end_date')
                    ->date()
                    ->label('Termina'),
                TextEntry::make('status')
                    ->badge()
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        if ($state == 'active') {
                            return 'Activo';
                        }else {
                            return 'Inactivo';
                        }
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    }),
                TextEntry::make('payment_status')
                    ->badge()
                    ->label('Estado de pago')
                    ->formatStateUsing(function ($state) {
                        if ($state == 'paid') {
                            return 'Pagado';
                        }elseif($state == 'partial') {
                            return 'Parcial';
                        }else {
                            return 'Pendiente';
                        }
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'danger',
                        'partial' => 'info',
                    }),
                TextEntry::make('customer.name')
                    ->label('Cliente'),
                TextEntry::make('plan.name')
                    ->label('Plan'),
                TextEntry::make('description')
                    ->label('Descripción'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Creado'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Modificado'),
            ]);
    }
}
