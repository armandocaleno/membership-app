<?php

namespace App\Filament\Resources\Incomes\Schemas;

use App\Models\Support;
use App\Models\Suscription;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class IncomeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del ingreso')
                ->schema([
                    TextEntry::make('number')
                        ->label('Número')
                        ->icon(Heroicon::User)
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold),
                    TextEntry::make('date')
                        ->date()
                        ->label('Fecha')
                        ->color(Color::Gray)
                        ->icon(Heroicon::Calendar),
                    TextEntry::make('total')
                        ->money()
                        ->label('Total')
                        ->color(Color::Gray),
                    TextEntry::make('attached_file')
                        ->label('Adjunto')
                        ->placeholder('-')
                        ->formatStateUsing(function ($state) {
                            $symbol = "/";
                            $position = strpos($state, $symbol);
                            if ($position !== false) {
                                $result = substr($state, $position + 1);
                                return $result;
                            }
                        })
                        ->color(Color::Gray),
                    TextEntry::make('paymentMethod.name')
                        ->label('F. de Pago')
                        ->color(Color::Gray)
                        ->icon(Heroicon::CreditCard),
                    TextEntry::make('incomeable_id')
                        ->label('Cliente')
                        ->icon(Heroicon::User)
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        ->color(Color::Gray)
                        ->state(function($record){
                            return $record->incomeable->customer->name;
                        }),
                    TextEntry::make('incomeable')
                        ->label('Recurso')
                        ->color(Color::Gray)
                        ->formatStateUsing(function($state){
                            $value = $state['number'];
                            return $value;
                        })
                        ->url(function($state){
                            $route = ""; 
                            if ($state instanceof Suscription) {
                                $route = route('filament.admin.resources.suscriptions.view',['record' => $state]);
                            }elseif ($state instanceof Support) {
                                $route = route('filament.admin.resources.supports.view',['record' => $state]);
                            }

                            return $route;
                        }),
                    TextEntry::make('description')
                        ->label('Descripción')
                        ->color(Color::Gray),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->placeholder('-')
                        ->label('Creado')
                        ->color(Color::Gray),
                    TextEntry::make('updated_at')
                        ->dateTime()
                        ->placeholder('-')
                        ->label('Modificado')
                        ->color(Color::Gray),
                ])->columns(2)
            ]);
    }
}
