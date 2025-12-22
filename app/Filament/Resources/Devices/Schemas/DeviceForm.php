<?php

namespace App\Filament\Resources\Devices\Schemas;

use App\Models\Customer;
use App\Models\Establishment;
use Filament\Forms\Components\Repeater;
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
                    ->relationship('deviceType', 'name')
                    ->required()
                    ->label('Tipo')
                    ->native(false),
                Select::make('customer_id')
                    ->label('Cliente')
                    ->default(null)
                    // ->default(fn() => Customer::latest()->first()?->id)
                    ->searchable()
                    ->options(function(){
                        return Customer::where('status', 'active')->pluck('name', 'id') ?? [];
                    })
                    ->getSearchResultsUsing(fn (string $search): array => Customer::query()
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('ruc', 'like', "{$search}%")
                        ->limit(50)
                        ->pluck('name', 'id')
                        ->all())
                    ->native(false)
                    ->reactive()
                    ->searchPrompt('Buscar por nombre o RUC del cliente.')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('establishment_id', null);
                        $set('device_id', null);
                    }),
                Select::make('establishment_id')
                    ->required()
                    ->label('Establecimiento')
                    // ->default(fn() => Establishment::latest()->first()?->id)
                    ->default(null)
                    ->options(function (callable $get) {
                        $customer_id = $get('customer_id');
                        return $customer_id ? Establishment::where('customer_id', $customer_id)->pluck('name', 'id') : [];
                    })
                    ->label('Establecimiento')
                    ->reactive()
                    ->native(false),
                Repeater::make('remoteDesktopSoftware')
                    ->schema([
                        TextInput::make('conecction_id')
                            ->label('Id conexión'),
                        TextInput::make('name')
                            ->label('Software'),
                    ])
                    ->addActionLabel('Añadir Software Remoto')
                    ->maxItems(3)
                    ->grid(2)
                    ->columnSpan('full')
                    ->label('Software de Escritorio Remoto'),
            ]);
    }
}
