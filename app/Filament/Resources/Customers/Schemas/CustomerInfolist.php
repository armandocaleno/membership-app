<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
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
                                ->color(Color::Gray),
                            TextEntry::make('phone')
                                ->label('Teléfono')
                                ->icon(Heroicon::DevicePhoneMobile),
                            TextEntry::make('email')
                                ->label('Email')
                                ->icon(Heroicon::Envelope)
                                ->color(Color::Gray),
                        ])
                        ->columnSpanFull()
                        // ->columns(2)
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
                        ]),
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

                 RepeatableEntry::make('establishments')
                    ->label('Establecimientos')
                    ->schema([
                        TextEntry::make('code')
                        ->label('Código')
                        ->color(Color::Gray),
                        TextEntry::make('name')
                        ->label('Nombre')
                        ->color(Color::Gray),
                        TextEntry::make('phone')
                        ->label('Teléfono')
                        ->color(Color::Gray),
                        TextEntry::make('address')
                        ->label('Dirección')
                        ->color(Color::Gray)
                        // ->columnSpan(2),
                ])
                ->columns(2)
                ->grid(2)
                ->columnSpanFull()
            ]);
    }
}
