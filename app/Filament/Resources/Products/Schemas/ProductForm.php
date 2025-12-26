<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->maxLength(100)
                    ->unique(Product::class, 'name', ignoreRecord:true),
                Select::make('type')
                    ->options(['service' => 'Servicio', 'hardware' => 'Bien'])
                    ->default('service')
                    ->required()
                    ->label('Tipo')
                    ->native(false),
                Textarea::make('description')
                    ->label('Descripción')
                    ->autosize()
                    ->rows(1)
            ]);
    }
}
