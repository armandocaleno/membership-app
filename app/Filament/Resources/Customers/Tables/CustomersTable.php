<?php

namespace App\Filament\Resources\Customers\Tables;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Customer;
use App\Models\Regime;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nombre')
                    ->sortable(),
                TextColumn::make('ruc')
                    ->searchable()
                    ->label('RUC')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('address')
                    ->searchable()
                    ->label('Dirección')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('province')
                    ->label('Provincia')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('city')
                    ->label('Ciudad')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('Teléfono')
                    ->toggleable(condition:false),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(condition: false),
                TextColumn::make('regime.name')
                    ->searchable()
                    ->label('Régimen SRI')
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ])
            ->filters([
                SelectFilter::make('regime_id')
                    ->label('Régimen')
                    ->indicator('Régimen seleccionado')
                    ->options(Regime::pluck('name', 'id'))
                    ->native(false),
                SelectFilter::make('province')
                    ->label('Provincia')
                    ->options(Customer::getProvinces())
                    ->native(false),
                SelectFilter::make('city')
                    ->label('Ciudad')
                    ->indicator('Ciudad seleccionada')
                    ->options(fn (): array => Customer::query()
                        ->select('city')
                        ->whereNotNull('city')
                        ->distinct()
                        ->orderBy('city')
                        ->pluck('city', 'city')
                        ->toArray())
                    ->searchable()
                    ->native(false),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(['active' => 'Activo', 'inactive' => 'Inactivo'])
                    ->native(false)

            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make()
                ->before(function (DeleteAction $action, $record) {
                        if ($record->suscriptions()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este cliente porque tiene suscripciones relacionadas.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }

                        if ($record->supports()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este cliente porque tiene soportes relacionadas.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }

                        if ($record->establishments()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este cliente porque tiene establecimientos relacionadas.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }
                    })
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('exportar')
                ->disableCsv()
                ->disableAdditionalColumns()
                ->withHiddenColumns()
                ->visible(fn(): bool => auth()->user()->can('Export:Customer'))
                ->extraViewData([
                    'title' => 'Reporte de clientes',
                    'date' => now()->format('d-m-Y H:i')
                ])
            ]);
    }
}
