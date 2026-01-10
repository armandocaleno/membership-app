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
                                ->weight(FontWeight::Bold)
                                ->columnSpan(4),
                            TextEntry::make('address')
                                ->label('Dirección')
                                ->icon(Heroicon::MapPin)
                                ->color(Color::Gray)
                                ->columnSpan(2),
                            TextEntry::make('email')
                                ->label('Email')
                                ->icon(Heroicon::Envelope)
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
                            TextEntry::make('phone')
                                ->label('Teléfono')
                                ->color(Color::Gray)
                                ->icon(Heroicon::DevicePhoneMobile),
                            Action::make('chat')
                                ->url(fn($record) => 'https://wa.me/593' . $record->phone . '?text=Estimado%20cliente')
                                ->icon(Heroicon::ChatBubbleOvalLeft)
                                ->label('WhatsApp')
                                ->badge()
                                ->color('success')
                                ->openUrlInNewTab()
                                ->disabled(fn($record) : bool => !$record->is_whatsapp),
                            
                        ])
                        ->columns(4)
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
            ]);
    }
}
