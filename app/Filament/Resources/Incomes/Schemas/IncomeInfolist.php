<?php

namespace App\Filament\Resources\Incomes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class IncomeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('number'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('attached_file'),
                TextEntry::make('paymentMethod.name')
                    ->label('Payment method'),
                TextEntry::make('incomeable_id')
                    ->numeric(),
                TextEntry::make('incomeable_type'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
