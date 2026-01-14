<?php

namespace App\Filament\Resources\Suscriptions\Tables;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Incomes\IncomeResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class SuscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->sortable()
                    ->searchable()
                    ->label('Número'),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->label('Inicia'),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->label('Termina'),
                TextColumn::make('customer.name')
                    ->sortable()
                    ->searchable()
                    ->label('Cliente')
                    ->url(fn($record):string => CustomerResource::getUrl('view', ['record' => $record->customer]))
                    ->description(fn ($record) :string => $record->customer->ruc),
                TextColumn::make('plan.name')
                    ->description(fn ($record) :string =>'$ ' . $record->plan->price)
                    ->sortable(),
                TextColumn::make('incomes.total')
                    ->label('Pagos')
                    ->listWithLineBreaks()
                    ->alignment('right')
                    ->url(fn($state):string =>IncomeResource::getUrl('view', ['record' => $state]))
                    ->placeholder('$0.00')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->prefix('$'),
                TextColumn::make('description')
                    ->searchable()
                    ->label('Descripción')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->label('Estado')
                    ->alignCenter()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == 'active') {
                            return 'Activo';
                        }else {
                            return 'Inactivo';
                        }
                    }),
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
                    ->sortable()
                    ->label('Creado')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Modificado')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
               SelectFilter::make('status')
                    ->label('Estado de suscripción')
                    ->options(['active' => 'Activo', 'inactive' => 'Inactivo'])
                    ->indicator('Estado')
                    ->native(false),
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
                SelectFilter::make('plan')
                    ->label('Plan')
                    ->relationship('plan', 'name')
                    ->preload()
                    ->native(false),
                Filter::make('start_range')
                    ->label('Inicio de suscripciones')
                    ->indicator('fechas')
                    ->schema([
                        DatePicker::make('sus_from')
                        ->label('Inicia desde'),
                        DatePicker::make('sus_until')
                        ->label('Inicia hasta')
                    ])
                    ->columns(2)
                    ->indicateUsing(function (array $data): array{
                        $indicators =[];

                        if (filled($data['sus_from'] ?? null)) {
                            $indicators[] = Indicator::make('Inician desde el: ' . Carbon::parse($data['sus_from'])->isoFormat('D MMMM YYYY'))
                            ->removeField('sus_from');
                        }
                        if (filled($data['sus_until'] ?? null)) {
                            $indicators[] = Indicator::make('Inician hasta el: ' . Carbon::parse($data['sus_until'])->isoFormat('D MMMM YYYY'))
                            ->removeField('sus_until');
                        }

                        return $indicators;
                    })
                    ->query(function (Builder $query, array $data) : Builder {
                        return $query
                            ->when(
                                filled($data['sus_from'] ?? null),
                                fn(Builder $query) => $query->whereDate('start_date', '>=', $data['sus_from'])
                            )
                            ->when(
                                filled($data['sus_until'] ?? null),
                                fn(Builder $query) => $query->whereDate('start_date', '<=', $data['sus_until'])
                            );
                    }),
                Filter::make('end_range')
                    ->label('Vencimiento de suscripciones')
                    ->indicator('Fechas expiracion')
                    ->schema([
                        DatePicker::make('end_from')
                        ->label('Termina desde'),
                        DatePicker::make('end_until')
                        ->label('Termina hasta')
                    ])
                    ->columns(2)
                    ->indicateUsing(function (array $data): array{
                        $indicators =[];

                        if (filled($data['end_from'] ?? null)) {
                            $indicators[] = Indicator::make('Terminan desde el: ' . Carbon::parse($data['end_from'])->isoFormat('D MMMM YYYY'))
                            ->removeField('end_from');
                        }
                        if (filled($data['end_until'] ?? null)) {
                            $indicators[] = Indicator::make('Terminan hasta el: ' . Carbon::parse($data['end_until'])->isoFormat('D MMMM YYYY'))
                            ->removeField('end_until');
                        }

                        return $indicators;
                    })
                    ->query(function (Builder $query, array $data) : Builder {
                        return $query
                            ->when(
                                filled($data['end_from'] ?? null),
                                fn(Builder $query) => $query->whereDate('end_date', '>=', $data['end_from'])
                            )
                            ->when(
                                filled($data['end_until'] ?? null),
                                fn(Builder $query) => $query->whereDate('end_date', '<=', $data['end_until'])
                            );
                    })
            ])
            ->filtersFormColumns(2)
            ->filtersFormSchema(fn(array $filters): array =>[
                Section::make('Estados')
                    ->schema([
                        $filters['status'],
                        $filters['payment_status']
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Rangos de fecha')
                    ->schema([
                        $filters['start_range'],
                        $filters['end_range']
                    ])
                    ->columnSpanFull(),
                Section::make('Planes y Cliente')
                    ->schema([
                        $filters['customer'],
                        $filters['ruc'],
                        $filters['plan']
                    ])
                    ->columns(2)
                    ->columnSpanFull()
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                ->before(function (DeleteAction $action, $record) {
                        if ($record->incomes()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este suscripción porque tiene ingresos relacionados.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }
                    })
            ])
            ->recordUrl(
                fn (Model $record): string => route('filament.admin.resources.suscriptions.view', ['record' => $record])
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
                ->visible(fn(): bool => auth()->user()->can('Export:Suscription'))
                ->extraViewData([
                    'title' => 'Reporte de suscripciones',
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
