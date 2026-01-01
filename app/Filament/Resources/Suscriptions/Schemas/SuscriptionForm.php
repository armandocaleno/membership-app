<?php

namespace App\Filament\Resources\Suscriptions\Schemas;

use App\Models\Customer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => Customer::query()
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('ruc', 'like', "{$search}%")
                        ->limit(50)
                        ->pluck('name', 'id')
                        ->all())
                    ->searchPrompt('Buscar por nombre o RUC del cliente.'),
                Select::make('plan_id')
                    ->relationship('plan', 'name')
                    ->default(null)
                    ->label('Plan')
                    ->required()
                    ->native(false)
                    ->afterStateUpdated(function($state, callable $set){
                        $set('price', $state);
                    })
                    ->reactive(),
                Select::make('price')
                    ->relationship('plan', 'price')
                    ->label('Total')
                    ->prefix('$')
                    ->disabled(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->autosize()
                    ->rows(1),
                Select::make('status')
                    ->options(['active' => 'Activo', 'inactive' => 'Inactivo'])
                    ->default('active')
                    ->required()
                    ->label('Estado')
                    ->native(false),
                Select::make('payment_status')
                    ->options(['paid' => 'Pagada', 'pending' => 'Pendiente', 'partial' => 'Parcial'])
                    ->default('pending')
                    ->required()
                    ->label('Estado de pago')
                    ->visible(fn ($operation) => $operation === 'create')
                    ->native(false),
            ]);
    }
}
