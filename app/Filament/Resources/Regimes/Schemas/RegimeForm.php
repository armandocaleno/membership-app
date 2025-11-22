<?php

namespace App\Filament\Resources\Regimes\Schemas;

use App\Models\Regime;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RegimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->maxLength(50)
                    ->unique(Regime::class, 'name', ignoreRecord: true),
            ]);
    }
}
