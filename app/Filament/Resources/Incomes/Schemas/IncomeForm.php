<?php

namespace App\Filament\Resources\Incomes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class IncomeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->required()
                    ->label('Numero'),
                DatePicker::make('date')
                    ->required()
                    ->label('Fecha'),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->label('Total'),
                Select::make('payment_method_id')
                    ->relationship('paymentMethod', 'name')
                    ->required()
                    ->label('F. Pago'),
                FileUpload::make('attached_file')
                    ->required()
                    ->label('Adjunto')
                    ->aboveContent([
                        Icon::make(Heroicon::BellAlert),
                        'Admite archivos PDF e imagenes JPEG y PNG'
                    ])
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png',])
                    ->maxSize(1024),
            ]);
    }
}
