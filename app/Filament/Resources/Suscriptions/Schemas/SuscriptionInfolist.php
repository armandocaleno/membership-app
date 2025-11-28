<?php

namespace App\Filament\Resources\Suscriptions\Schemas;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Customer;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SuscriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                ->schema([
                    Section::make('Información de la suscripción')
                        ->schema([
                            TextEntry::make('number')
                                ->label('Número'),
                            TextEntry::make('start_date')
                                ->date()
                                ->label('Inicia'),
                            TextEntry::make('end_date')
                                ->date()
                                ->label('Termina'),
                            TextEntry::make('plan.name')
                                ->label('Plan'),
                            TextEntry::make('description')
                                ->label('Descripción'),
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
                    ]),
                    Section::make('Informacion del cliente')
                        ->schema([
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
                                ->label('Cliente')
                                ->url(fn($record):string => CustomerResource::getUrl('view', ['record' => $record->customer])),
                            TextEntry::make('created_at')
                                ->dateTime()
                                ->placeholder('-')
                                ->label('Creado'),
                            TextEntry::make('updated_at')
                                ->dateTime()
                                ->placeholder('-')
                                ->label('Modificado'),
                        ])
                ])
                ->columnSpanFull()
            ]);
    }
}
