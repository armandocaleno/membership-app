<?php

namespace App\Filament\Resources\Suscriptions\Schemas;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class SuscriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                ->schema([
                    Section::make('Información de la suscripción')
                        ->schema([
                            TextEntry::make('number')
                                ->label('Número')
                                ->color(Color::Gray)
                                ->size(TextSize::Large)
                                ->weight(FontWeight::Bold),
                            TextEntry::make('start_date')
                                ->date()
                                ->label('Inicia')
                                ->color(Color::Gray)
                                ->icon(Heroicon::Clock),
                            TextEntry::make('end_date')
                                ->date()
                                ->label('Termina')
                                ->color(Color::Gray)
                                ->icon(Heroicon::Clock),
                            TextEntry::make('customer.name')
                                ->label('Cliente')
                                ->icon(Heroicon::User)
                                ->size(TextSize::Large)
                                ->weight(FontWeight::Bold)
                                ->url(fn($record):string => CustomerResource::getUrl('view', ['record' => $record->customer])),
                            TextEntry::make('plan.name')
                                ->label('Plan')
                                ->color(Color::Gray),
                            TextEntry::make('plan.price')
                                ->label('Total')
                                ->prefix('$')
                                ->color(Color::Gray),
                            TextEntry::make('description')
                                ->label('Descripción')
                                ->color(Color::Gray),
                            TextEntry::make('status')
                                ->badge()
                                ->label('Estado')
                                ->formatStateUsing(function ($state) {
                                    if ($state == 'active') {
                                        return 'Activa';
                                    }else {
                                        return 'Vencida';
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
                        ])->columns(4),
                ])->columnSpanFull()
                
            ]);
    }
}
