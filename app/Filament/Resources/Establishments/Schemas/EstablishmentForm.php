<?php

namespace App\Filament\Resources\Establishments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EstablishmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->default(null)
                    ->label('Código'),
                TextInput::make('name')
                    ->required()
                    ->label('Nombre'),
                TextInput::make('address')
                    ->default(null)
                    ->label('Dirección'),
                TextInput::make('phone')
                    ->tel()
                    ->default(null)
                    ->label('Teléfono'),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                    ->default('active')
                    ->required()
                    ->label('Estado'),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required()
                    ->searchable()
                    ->label('Cliente'),
            ]);
    }
}
