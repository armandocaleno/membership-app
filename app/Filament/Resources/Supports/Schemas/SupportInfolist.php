<?php

namespace App\Filament\Resources\Supports\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SupportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('date')
                    ->date()
                    ->label('Fecha'),
                TextEntry::make('number')
                    ->label('Número'),
                TextEntry::make('detail')
                    ->label('Detalle')
                    ->placeholder('-'),
                TextEntry::make('comments')
                    ->label('Comentarios')
                    ->placeholder('-'),
                TextEntry::make('attached_file')
                    ->placeholder('-')
                    ->label('Adjunto'),
                TextEntry::make('customer.name')
                    ->label('Cliente'),
                TextEntry::make('establishment.name')
                    ->placeholder('-')
                    ->label('Establecimiento'),
                TextEntry::make('device.name')
                    ->placeholder('-')
                    ->label('Dispositivo'),
                TextEntry::make('total')
                    ->money()
                    ->label('Total'),
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
