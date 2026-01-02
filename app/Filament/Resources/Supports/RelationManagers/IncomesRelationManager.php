<?php

namespace App\Filament\Resources\Supports\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IncomesRelationManager extends RelationManager
{
    protected static string $relationship = 'incomes';
    protected static ?string $title = 'Ingresos';

    public static function getModelLabel(): string
    {
        return "Ingreso";
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->required()
                    ->label('Número'),
                DatePicker::make('date')
                    ->required()
                    ->label('Fecha')
                    ->default(fn():string => $this->getOwnerRecord()->date),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->label('Total')
                    ->prefix('$')
                    ->default(fn():float => $this->getOwnerRecord()->saldo()),
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
                    ->moveFiles()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->columns([
                TextColumn::make('number')
                    ->label('Número'),
                TextColumn::make('date')
                    ->label('Fecha')
                    ->date(),
                TextColumn::make('total')
                    ->prefix('$')
                    ->sortable()
                    ->label('Total')
                    ->weight('bold'),
                TextColumn::make('attached_file')
                    ->label('Adjunto')
                    ->formatStateUsing(function ($state) {
                        $symbol = "/";
                        $position = strpos($state, $symbol);
                        if ($position !== false) {
                            $result = substr($state, $position + 1);
                            return $result;
                        }
                    })
                    ->url(fn ($record) => Storage::url($record->attached_file), true) // El segundo argumento 'true' indica que se descargará
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('paymentMethod.name')
                    ->searchable()
                    ->label('F. Pago'),
                TextColumn::make('description')
                    ->searchable()
                    ->label('Descripción')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creado')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Modificado')
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->headerActions([
                CreateAction::make()
                ->successNotificationTitle('Ingreso registrado!')
                ->createAnother(false)
                ->before(function (CreateAction $action, array $data) {
                    if ($this->getOwnerRecord()->saldo() < $data['total'] || $this->getOwnerRecord()->payment_status == 'paid') {
                        Notification::make()
                            ->warning()
                            ->title('Monto no válido!')
                            ->body('El valor a pagar es mayor que el saldo del soporte o su estado es pagado.')
                            ->persistent()
                            ->send();
                    
                        $action->halt();
                    }
                }),
            ])
            ->recordActions([
                DeleteAction::make(),
            ]);
    }
}
