<?php

namespace App\Filament\Resources\Devices\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('serial')
                    ->label('No. de Serie')
                    ->default(null),
                TextInput::make('description')
                    ->default(null)
                    ->label('Descripción'),
                Select::make('device_type_id')
                    ->relationship('type', 'name')
                    ->required()
                    ->label('Tipo'),
                Select::make('establishment_id')
                    ->relationship('establishment', 'name')
                    ->required()
                    ->label('Establecimiento'),
            ]);
    }
}
