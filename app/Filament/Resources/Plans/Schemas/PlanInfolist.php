<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PlanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nombre'),
                TextEntry::make('devices')
                    ->numeric()
                    ->placeholder('-')
                    ->label('Dispositivos'),
                TextEntry::make('months')
                    ->numeric()
                    ->label('Meses'),
                TextEntry::make('price')
                    ->money()
                    ->label('Precio')
                    ->prefix('$'),
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
                TextEntry::make('product.name')
                    ->label('Producto'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Creado'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Actualizado'),
            ]);
    }
}
