<?php

namespace App\Filament\Resources\Suscriptions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SuscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->required()
                    ->label('Inicia')
                    ->default(now()),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->default(null)
                    ->label('Cliente')
                    ->required()
                    ->native(false)
                    ->searchable(),
                Select::make('plan_id')
                    ->relationship('plan', 'name')
                    ->default(null)
                    ->label('Plan')
                    ->required()
                    ->native(false),
                Select::make('status')
                    ->options(['active' => 'Activo', 'inactive' => 'Inactivo'])
                    ->default('active')
                    ->required()
                    ->label('Estado'),
                Select::make('payment_status')
                    ->options(['paid' => 'Pagada', 'pending' => 'Pendiente', 'partial' => 'Parcial'])
                    ->default('pending')
                    ->required()
                    ->label('Estado de pago'),
            ]);
    }
}
