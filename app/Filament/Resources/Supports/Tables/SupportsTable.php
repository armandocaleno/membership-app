<?php

namespace App\Filament\Resources\Supports\Tables;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Incomes\IncomeResource;
use App\Models\Establishment;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SupportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->label('Fecha')
                    ->sortable(),
                TextColumn::make('number')
                    ->searchable()
                    ->label('Número'),
                TextColumn::make('detail')
                    ->searchable()
                    ->label('Detalle')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comments')
                    ->searchable()
                    ->label('Comentarios')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable()
                    ->url(fn($record):string => CustomerResource::getUrl('view', ['record' => $record->customer]))
                    ->description(fn ($record) :string => $record->customer->ruc),
                TextColumn::make('establishment.name')
                    ->label('Establecimiento')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('device.name')
                    ->label('Dispositivo')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total')
                    ->prefix('$')
                    ->label('Total')
                    ->weight('bold'),
                TextColumn::make('incomes.total')
                    ->label('Pagos')
                    ->listWithLineBreaks()
                    ->prefix('$')
                    ->alignment('right')
                    ->url(fn($state):string =>IncomeResource::getUrl('view', ['record' => $state]))
                    ->placeholder('$0.00'),
                TextColumn::make('attached_file')
                    ->label('Adjunto')
                    ->formatStateUsing(function ($state) {
                        if ($state !== null) {
                            $symbol = "/";
                            $position = strpos($state, $symbol);
                            if ($position !== false) {
                                $result = substr($state, $position + 1);
                                return $result;
                            }
                        }
                    })
                    ->url(fn ($record) => Storage::url($record->attached_file), true) // El segundo argumento 'true' indica que se descargará
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('-'),
                TextColumn::make('payment_status')
                    ->badge()
                    ->label('Estado de pago')
                    ->alignCenter()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'danger',
                        'partial' => 'info',
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == 'paid') {
                            return 'Pagado';
                        }elseif($state == 'partial') {
                            return 'Parcial';
                        }else {
                            return 'Pendiente';
                        }
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Creado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Modificado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Estado de pago')
                    ->options(['paid' => 'Pagadas', 'pending' => 'Pendientes', 'partial' => 'Parciales'])
                    ->indicator('Estado de pago')
                    ->native(false),
                SelectFilter::make('customer')
                    ->label('Cliente')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),
                SelectFilter::make('ruc')
                    ->label('RUC')
                    ->relationship('customer', 'ruc')
                    ->searchable()
                    ->native(false),
                Filter::make('customer_establishment')
                    ->label('Establecimiento')
                    ->indicator('Establecimiento')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Cliente')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->native(false),
                        Select::make('establishment_id')
                            ->label('Establecimiento')
                            ->options(function(Get $get) : array {
                                $customerId = $get('customer_id');

                                if (! $customerId) {
                                    return [];
                                }

                                return Establishment::query()
                                        ->where('customer_id', $customerId)
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->disabled(fn (Get $get) : bool => ! filled($get('customer_id')))
                            ->native(false)
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->query(function(Builder $query, array $data) : Builder {
                                return $query
                                    ->when(
                                        filled($data['customer_id'] ?? null),
                                        fn (Builder $query) : Builder => $query->where('customer_id', $data['customer_id'])
                                    )
                                    ->when(
                                        filled($data['establishment_id'] ?? null),
                                        fn (Builder $query ) : Builder => $query->where('establishment_id', $data['establishment_id'])
                                    );
                            }),
                Filter::make('date_range')
                    ->label('Fecha de registro')
                    ->indicator('Fecha')
                    ->schema([
                        DatePicker::make('date_from')
                        ->label('Desde'),
                        DatePicker::make('date_until')
                        ->label('Hasta')
                    ])->columnSpanFull()
                    ->columns(2)
                    ->indicateUsing(function (array $data): array{
                        $indicators =[];
                        Carbon::setLocale('es');
                        if (filled($data['date_from'] ?? null)) {
                            $indicators[] = Indicator::make('Desde: ' . Carbon::parse($data['date_from'])->locale('es')->isoFormat('D MMMM YYYY'))
                            ->removeField('date_from');
                        }
                        if (filled($data['date_until'] ?? null)) {
                            $indicators[] = Indicator::make('Hasta: ' . Carbon::parse($data['date_until'])->locale('es')->isoFormat('D MMMM YYYY'))
                            ->removeField('date_until');
                        }

                        return $indicators;
                    })
                    ->query(function (Builder $query, array $data) : Builder {
                        return $query
                            ->when(
                                filled($data['date_from'] ?? null),
                                fn(Builder $query) => $query->whereDate('date', '>=', $data['date_from'])
                            )
                            ->when(
                                filled($data['date_until'] ?? null),
                                fn(Builder $query) => $query->whereDate('date', '<=', $data['date_until'])
                            );
                    })
            ])
            ->filtersFormColumns(2)
            ->filtersFormSchema(fn(array $filters): array =>[
                Section::make('Estado y cliente')
                ->schema([
                    $filters['payment_status'],
                    $filters['customer'],
                    $filters['ruc']
                ])
                ->columns(2)
                ->columnSpanFull(),
                Section::make('Fechas')
                ->schema([
                    $filters['date_range']
                ])
                ->columns(2)
                ->columnSpanFull(),
                Section::make('Establecimientos')
                ->schema([
                    $filters['customer_establishment']
                ])
                ->columns(2)
                ->columnSpanFull(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                ->before(function (DeleteAction $action, $record) {
                        if ($record->incomes()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este soporte porque tiene ingresos relacionados.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }
                    })
            ])
            ->recordUrl(
                fn (Model $record): string => route('filament.admin.resources.supports.view', ['record' => $record])
            )
            ->groups([
                Group::make('customer.name')
                ->label('Cliente'),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('exportar')
                ->disableCsv()
                ->disableAdditionalColumns()
                ->withHiddenColumns()
                ->visible(fn(): bool => auth()->user()->can('Export:Support'))
                ->extraViewData([
                    'title' => 'Reporte de soportes',
                    'date' => now()->format('d-m-Y H:i')
                ])
                ->formatStates([
                    'incomes.total' => function (?Model $record){
                        $incomes = $record->incomes->pluck('total');
                        $totals = $incomes->toArray();
                        return implode(', ', $totals);
                    }
                ])
            ]);
    }
}
