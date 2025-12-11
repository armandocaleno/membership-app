<?php

namespace App\Filament\Resources\Supports\Schemas;

use App\Models\Device;
use App\Models\Establishment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Filters\SelectFilter;

class SupportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->label('Fecha')
                    ->default(now())
                    ->required(),
                TextInput::make('number')
                    ->label('Número')
                    ->required(),
                Textarea::make('detail')
                    ->default(null)
                    ->label('Detalle')
                    ->autosize()
                    ->rows(1),
                Textarea::make('comments')
                    ->default(null)
                    ->label('Comentarios')
                    ->autosize()
                    ->rows(1),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label('Cliente')
                    ->searchable()
                    ->required()
                    ->native(false)
                    ->default(null)
                    ->reactive()
                    ->searchPrompt('Seleccione un cliente para cargar los establecimientos')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('establishment_id', null);
                    }),
                Select::make("establishment_id")
                    ->options(function (callable $get) {
                        $customer_id = $get('customer_id');
                        return $customer_id ? Establishment::where('customer_id', $customer_id)->pluck('name', 'id') : [];
                    })
                    ->label('Establecimiento')
                    ->reactive()
                    ->default(null)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('device_id', null);
                    }),
                Select::make("device_id")
                    ->options(function (callable $get) {
                        $establishment_id = $get('establishment_id');
                        return $establishment_id ? Device::where('establishment_id', $establishment_id)->pluck('description', 'id') : [];
                    })
                    ->label('Dispositivo')
                    ->reactive()
                    ->default(null),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('Total')
                    ->default('0.00'),
                Select::make('payment_status')
                    ->options(['paid' => 'Pagada', 'pending' => 'Pendiente', 'partial' => 'Parcial'])
                    ->default('pending')
                    ->required()
                    ->label('Estado de pago'),
                FileUpload::make('attached_file')
                    ->label('Adjunto')
                    ->disk('public')
                    ->directory('support-attachments')
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
