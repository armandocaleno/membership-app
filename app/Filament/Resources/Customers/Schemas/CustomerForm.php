<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->maxLength(50),
                TextInput::make('ruc')
                    ->default(null)
                    ->label('RUC')
                    ->unique(Customer::class, 'ruc', ignoreRecord: true)
                    ->rules(['digits:13', 'numeric'])
                    ->validationAttribute('RUC'),
                TextInput::make('address')
                    ->default(null)
                    ->label('Dirección')
                    ->maxLength(100),
                TextInput::make('phone')
                    ->tel()
                    ->default(null)
                    ->label('Teléfono')
                    ->rules(['digits:10', 'numeric'])
                    ->helperText('Número telefónico sin símbolos ni espacios'),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->default(null),
                Select::make('province')
                    ->options(Customer::getProvinces())
                    ->label('Provincia')
                    ->native(false),
                TextInput::make('city')
                    ->label('Ciudad')
                    ->default(null),
                Select::make('status')
                    ->options(['active' => 'Activo', 'inactive' => 'Inactivo'])
                    ->default('active')
                    ->required()
                    ->label('Estado')
                    ->native(false),
                Select::make('regime_id')
                    ->relationship('regime', 'name')
                    ->default(null)
                    ->label('Régimen SRI')
                    ->native(false),
                Textarea::make('description')
                    ->label('Descripción')
                    ->autosize()
                    ->rows(1)
            ]);
    }
}
