<?php

namespace App\Filament\Resources\Incomes\Schemas;

use App\Models\Support;
use App\Models\Suscription;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class IncomeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('number')
                    ->label('Número'),
                TextEntry::make('date')
                    ->date()
                    ->label('Fecha'),
                TextEntry::make('total')
                    ->money()
                    ->label('Total'),
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
                    }),
                TextEntry::make('paymentMethod.name')
                    ->label('F. de Pago'),
                TextEntry::make('incomeable_id')
                    ->label('Cliente')
                    ->state(function($record){
                        return $record->incomeable->customer->name;
                    }),
                TextEntry::make('incomeable')
                    ->label('Recurso')
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
