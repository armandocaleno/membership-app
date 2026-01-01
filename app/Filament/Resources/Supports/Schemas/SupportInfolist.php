<?php

namespace App\Filament\Resources\Supports\Schemas;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class SupportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del soporte')
                ->schema([
                    TextEntry::make('number')
                        ->label('Número')
                        ->color(Color::Gray)
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold),
                    TextEntry::make('date')
                        ->date()
                        ->label('Fecha')
                        ->color(Color::Gray)
                        ->icon(Heroicon::Calendar),
                    TextEntry::make('customer.name')
                        ->label('Cliente')
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        ->url(fn($record):string => CustomerResource::getUrl('view', ['record' => $record->customer]))
                        ->color(Color::Gray)
                        ->icon(Heroicon::User),
                    TextEntry::make('detail')
                        ->label('Detalle')
                        ->placeholder('-')
                        ->color(Color::Gray),
                    TextEntry::make('comments')
                        ->label('Comentarios')
                        ->placeholder('-')
                        ->color(Color::Gray),
                    TextEntry::make('attached_file')
                        ->placeholder('-')
                        ->label('Adjunto')
                        ->color(Color::Gray)
                        ->formatStateUsing(function ($state) {
                            if ($state !== null) {
                                $symbol = "/";
                                $position = strpos($state, $symbol);
                                if ($position !== false) {
                                    $result = substr($state, $position + 1);
                                    return $result;
                                }
                            }
                        }),
                    TextEntry::make('establishment.name')
                        ->placeholder('-')
                        ->label('Establecimiento')
                        ->color(Color::Gray)
                        ->icon(Heroicon::HomeModern),
                    TextEntry::make('device.description')
                        ->placeholder('-')
                        ->label('Dispositivo')
                        ->color(Color::Gray)
                        ->icon(Heroicon::ComputerDesktop),
                    TextEntry::make('total')
                        ->money()
                        ->label('Total')
                        ->color(Color::Gray),
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
                        ->label('Creado')
                        ->color(Color::Gray),
                    TextEntry::make('updated_at')
                        ->dateTime()
                        ->placeholder('-')
                        ->label('Modificado')
                        ->color(Color::Gray)
                ])->columns(4)->columnSpanFull(),
            ]);
    }
}
