<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Filament\Resources\Devices\DeviceResource;
use App\Filament\Resources\Establishments\EstablishmentResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Model;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                ->schema([
                        Section::make('Información del cliente')
                        ->icon(Heroicon::User)
                        ->description('Información detallada acerca del cliente')
                        ->schema([
                            TextEntry::make('name')
                                ->label('Nombre')
                                ->size(TextSize::Large)
                                ->weight(FontWeight::Bold),
                            TextEntry::make('address')
                                ->label('Dirección')
                                ->icon(Heroicon::MapPin)
                                ->color(Color::Gray)
                                ->columnSpan(2),
                            TextEntry::make('province')
                                ->label('Provincia')
                                ->icon(Heroicon::Flag)
                                ->color(Color::Gray),
                            TextEntry::make('city')
                                ->label('Ciudad')
                                ->icon(Heroicon::Map)
                                ->color(Color::Gray),
                            TextEntry::make('email')
                                ->label('Email')
                                ->icon(Heroicon::Envelope)
                                ->color(Color::Gray),
                            TextEntry::make('phone')
                                ->label('Teléfono')
                                ->color(Color::Gray)
                                ->icon(Heroicon::DevicePhoneMobile),
                            Action::make('chat')
                                ->url(fn(Get $get) => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $get('phone')))
                                ->icon(Heroicon::ChatBubbleOvalLeft)
                                ->label('WhatsApp')
                                ->badge()
                                ->color('success')
                                ->openUrlInNewTab()
                                ->disabled(fn($record) : bool => !$record->is_whatsapp),
                            
                        ])
                        ->columns(3)
                ]),

                Grid::make(1)
                ->schema([
                    Section::make('Tributario')
                    ->schema([
                        TextEntry::make('ruc')
                            ->label('RUC')
                            ->icon(Heroicon::Identification)
                            ->color(Color::Gray)
                            ->weight(FontWeight::Bold),
                        TextEntry::make('regime.name')
                            ->label('Régimen SRI')
                            ->color(Color::Gray),
                    ])
                    ->columns(2),
                    Section::make('Estado')
                    ->schema([
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
                        TextEntry::make('description')
                            ->label('Descripción')
                            ->columnSpan(3)
                    ])->columns(4)
                ]),

                // establishments and devices
                Grid::make(1)
                 ->schema([
                    RepeatableEntry::make('establishments')
                    ->label('Establecimientos')
                    ->schema([
                        Section::make('Datos del establecimiento')
                        ->schema([
                            TextEntry::make('code')
                            ->label('Código')
                            ->color(Color::Gray),
                            TextEntry::make('name')
                            ->label('Nombre')
                            ->color(Color::Gray),
                            TextEntry::make('phone')
                            ->label('Teléfono')
                            ->color(Color::Gray)
                            ->icon(Heroicon::Phone),
                            TextEntry::make('address')
                            ->label('Dirección')
                            ->color(Color::Gray)
                            ->icon(Heroicon::MapPin),
                            Action::make('edit')
                            ->label('Editar')
                            ->url(fn (Model $record) => EstablishmentResource::getUrl('edit', ['record' => $record]))
                            ->visible(auth()->user()->can('Update:Establishment') )
                        ])->columns(2)->columnSpan(2),
                        
                        
                        RepeatableEntry::make('devices')
                        ->label('Dispositivos')
                        ->schema([
                            TextEntry::make('serial')
                            ->label('Serial')
                            ->color(Color::Gray),
                             TextEntry::make('description')
                            ->label('Descripcion')
                            ->color(Color::Gray),
                             TextEntry::make('deviceType.name')
                            ->label('Tipo')
                            ->color(Color::Gray),
                            Action::make('edit')
                            ->label('Editar')
                            ->url(fn (Model $record) => DeviceResource::getUrl('edit', ['record' => $record]))
                            ->visible(auth()->user()->can('Update:Device') ),
                        ])
                        ->columns(3)
                        ->columnSpan(2)
                    ])
                ->columns(4)
                 ])->columnSpan('full'),

                // suscriptions and payments
                  Grid::make(1)
                 ->schema([
                    RepeatableEntry::make('suscriptions')
                    ->label('Suscripciones')
                    ->schema([
                        Section::make('Datos de la suscripción')
                        ->schema([
                            TextEntry::make('number')
                            ->label('Número')
                            ->color(Color::Gray),
                            TextEntry::make('start_date')
                            ->label('Inicia')
                            ->color(Color::Gray)
                            ->icon(Heroicon::Clock)
                            ->date(),
                            TextEntry::make('end_date')
                            ->label('Termina')
                            ->color(Color::Gray)
                            ->icon(Heroicon::Clock)
                            ->date(),
                            TextEntry::make('plan.name')
                            ->label('Plan')
                            ->color(Color::Gray)
                            ->icon(Heroicon::Calendar),
                            TextEntry::make('status')
                            ->label('Estado')
                            ->badge()
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
                            TextEntry::make('payment_status')
                            ->label('Estado de pago')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'paid' => 'success',
                                'pending' => 'danger',
                                'partial' => 'info',
                            })
                            ->formatStateUsing(function ($state) {
                                if ($state == 'paid') {
                                    return 'Pagado';
                                }elseif($state == 'partial') {
                                    return 'Parcial';
                                }else {
                                    return 'Pendiente';
                                }
                            }),
                        ])->columns(2)->columnSpan(2),
                        
                        // payments
                        RepeatableEntry::make('incomes')
                        ->label('Ingresos')
                        ->schema([
                            TextEntry::make('date')
                            ->label('Fecha')
                            ->color(Color::Gray)
                            ->date(),
                             TextEntry::make('number')
                            ->label('Número')
                            ->color(Color::Gray),
                             TextEntry::make('total')
                            ->label('Total')
                            ->color(Color::Gray)
                            ->prefix('$'),
                            TextEntry::make('paymentMethod.name')
                            ->label('F. pago')
                            ->color(Color::Gray)
                            ->icon(Heroicon::CreditCard),
                        ])
                        ->columns(4)
                        ->columnSpan(2)
                    ])
                ->columns(4)
                 ])->columnSpan('full'),

                 // supports and payments
                  Grid::make(1)
                 ->schema([
                    RepeatableEntry::make('supports')
                    ->label('Soportes')
                    ->schema([
                        Section::make('Datos del soporte')
                        ->schema([
                            TextEntry::make('number')
                            ->label('Número')
                            ->color(Color::Gray),
                            TextEntry::make('date')
                            ->label('Fecha')
                            ->color(Color::Gray)
                            ->icon(Heroicon::Clock)
                            ->date(),
                            TextEntry::make('total')
                            ->label('Total')
                            ->color(Color::Gray)
                            ->prefix('$'),
                            TextEntry::make('payment_status')
                            ->label('Estado de pago')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'paid' => 'success',
                                'pending' => 'danger',
                                'partial' => 'info',
                            })
                            ->formatStateUsing(function ($state) {
                                if ($state == 'paid') {
                                    return 'Pagado';
                                }elseif($state == 'partial') {
                                    return 'Parcial';
                                }else {
                                    return 'Pendiente';
                                }
                            })
                        ])->columns(2)->columnSpan(2),
                        
                        // payments
                        RepeatableEntry::make('incomes')
                        ->label('Ingresos')
                        ->schema([
                            TextEntry::make('date')
                            ->label('Fecha')
                            ->color(Color::Gray)
                            ->date(),
                             TextEntry::make('number')
                            ->label('Número')
                            ->color(Color::Gray),
                             TextEntry::make('total')
                            ->label('Total')
                            ->color(Color::Gray)
                            ->prefix('$'),
                            TextEntry::make('paymentMethod.name')
                            ->label('F. pago')
                            ->color(Color::Gray)
                            ->icon(Heroicon::CreditCard),
                        ])
                        ->columns(4)
                        ->columnSpan(2)
                    ])
                    ->columns(4)
                 ])
                 ->columnSpan('full')
            ]);
    }
}
