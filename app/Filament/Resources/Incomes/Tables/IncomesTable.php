<?php

namespace App\Filament\Resources\Incomes\Tables;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Support;
use App\Models\Suscription;
use Filament\Actions\DeleteAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Customer;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class IncomesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    // ->searchable()
                    ->label('Número'),
                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->label('Fecha'),
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
                TextColumn::make('incomeable_id')
                    ->label('Cliente')
                    ->state(function($record){
                        return $record->incomeable->customer->name;
                    })
                    ->searchable()
                    ->url(fn($record): string => CustomerResource::getUrl('view', ['record' => $record->incomeable->customer])),
                TextColumn::make('incomeable')
                    ->label('Recurso')
                    ->formatStateUsing(function($state){
                        $value = $state['number'];
                        return $value;
                    })
                    ->description(function($state){
                        $value = "";
                        if ($state instanceof Suscription) {
                            $value = "Suscripción";
                        }elseif ($state instanceof Support) {
                            $value = "Soporte";
                        }

                        return $value;
                    })
                    ->url(function($state){
                            $route = ""; 
                            if ($state instanceof Suscription) {
                                $route = route('filament.admin.resources.suscriptions.view',['record' => $state]);
                            }elseif ($state instanceof Support) {
                                $route = route('filament.admin.resources.supports.view',['record' => $state]);
                            }

                            return $route;
                        }),
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
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('payment_method_id')
                    ->label('F. de pago')
                    ->options(PaymentMethod::pluck('name', 'id'))
                    ->indicator('Forma de pago'),
                SelectFilter::make('incomeable_type')
                    ->label('Recurso')
                    ->options(['App\Models\Suscription' => 'Suscripción', 'App\Models\Support' => 'Soporte'])
                    ->preload(),
                // Filter::make('customer')
                //     ->schema([
                //         Select::make('select_customer')
                //         ->label('Cliente')
                //         ->options(Customer::where('status', 'active')->pluck('name', 'id'))
                //         ->searchable()
                //     ])
                    // ->modifyQueryUsing(fn(Builder $query) =>
                    //     $query->join('suscriptions as sus', 'incomes.incomeable_id', '=', 'sus.id')
                    //             ->selectRaw('incomes.*, sus.customer_id')
                    // )
                    // ->query(function (Builder $query, array $data) : Builder {
                    //     dd($query);
                    //     return $query
                    //             ->when(
                    //                 filled($data['select_customer'] ?? null),
                                    
                    //                 fn(Builder $query) => $query
                    //                                         ->where('customer_id', $data['select_customer'])
                    //             );
                    // }),
                Filter::make('date_range')
                    ->label('Fecha de registro')
                    ->indicator('Fecha')
                    ->schema([
                        DatePicker::make('date_from')
                        ->label('Desde'),
                        DatePicker::make('date_until')
                        ->label('Hasta')
                    ])
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
            ->recordActions([
                DeleteAction::make()
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('exportar')
                ->disableCsv()
                ->disableAdditionalColumns()
                ->withHiddenColumns()
            ]);
    }

    
}
