<?php

namespace App\Filament\Resources\Incomes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                    ->label('Número')
                    ->autofocus(),
                DatePicker::make('date')
                    ->required()
                    ->label('Fecha')
                    ->default(now()),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->label('Total')
                    ->prefix('$'),
                    // ->description(fn ($record) :string => $record->total),
                Select::make('payment_method_id')
                    ->relationship('paymentMethod', 'name')
                    ->required()
                    ->label('F. Pago'),
                Textarea::make('description')
                    ->label('Descripción')
                    ->autosize()
                    ->rows(1),
                FileUpload::make('attached_file')
                    ->label('Adjunto')
                    ->disk('public')
                    ->directory('income-attachments')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->aboveContent([
                        Icon::make(Heroicon::BellAlert),
                        'Admite archivos PDF e imágenes JPEG y PNG'
                    ])
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png',])
                    ->maxSize(1024)
                    ->openable()
                    ->moveFiles(),
        ]);       
    }
}
