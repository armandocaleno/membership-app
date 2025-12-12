<?php

namespace App\Filament\Resources\Establishments\Schemas;

use App\Models\Customer;
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
                Select::make('province')
                    ->options(static::getProvinces())
                    ->label('Provincia'),
                TextInput::make('city')
                    ->label('Ciudad')
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null)
                    ->label('Teléfono'),
                Select::make('status')
                    ->options(['active' => 'Activo', 'inactive' => 'Inactivo'])
                    ->default('active')
                    ->required()
                    ->label('Estado'),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => Customer::query()
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('ruc', 'like', "{$search}%")
                        ->limit(50)
                        ->pluck('name', 'id')
                        ->all())
                    ->searchPrompt('Buscar por nombre o RUC del cliente.')
                    ->label('Cliente'),
            ]);
    }

    protected static function getProvinces() : array {
        return [
           "Azuay",
            "Bolívar",
            "Cañar",
            "Carchi",
            "Chimborazo",
            "Cotopaxi",
            "El Oro",
            "Esmeraldas",
            "Galápagos",
            "Guayas",
            "Imbabura",
            "Loja",
            "Los Ríos",
            "Manabí",
            "Morona Santiago",
            "Napo",
            "Orellana",
            "Pastaza",
            "Pichincha",
            "Santa Elena",
            "Santo Domingo de los Tsáchilas",
            "Sucumbíos",
            "Tungurahua",
            "Zamora Chinchipe"
        ];
    }
}
