<?php

namespace App\Filament\Resources\Plans\Schemas;

use App\Models\Plan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->maxLength(50)
                    ->unique(Plan::class, 'name', ignoreRecord:true),
                TextInput::make('devices')
                    ->numeric()
                    ->default(null)
                    ->label('Dispositivos'),
                TextInput::make('months')
                    ->required()
                    ->numeric()
                    ->label('Meses'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('Precio'),
                Select::make('status')
                    ->options(['active' => 'Activo', 'inactive' => 'Inactivo'])
                    ->default('active')
                    ->required()
                    ->label('Estado')
                    ->native(false),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->label('Producto')
                    ->native(false),
                Textarea::make('description')
                    ->label('Descripción')
                    ->autosize()
                    ->rows(1)
            ]);
    }
}
