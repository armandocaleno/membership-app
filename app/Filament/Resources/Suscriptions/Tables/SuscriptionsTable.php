<?php

namespace App\Filament\Resources\Suscriptions\Tables;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Incomes\IncomeResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                    ->url(fn($record):string => CustomerResource::getUrl('view', ['record' => $record->customer])),
                TextColumn::make('plan.name')
                    ->description(fn ($record) :string =>'$ ' . $record->plan->price)
                    ->sortable(),
                TextColumn::make('incomes')
                    ->label('Pagos')
                    ->listWithLineBreaks()
                    ->formatStateUsing(fn($state):string => '$ ' . $state->total)
                    ->alignment('right')
                    ->url(fn($state):string =>IncomeResource::getUrl('view', ['record' => $state]))
                    ->placeholder('0.00'),
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
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('Activos')
                ->query(fn (Builder $query): Builder => $query->where('status', 'active')),
                Filter::make('Inactivos')
                ->query(fn (Builder $query): Builder => $query->where('status', 'inactive')),
                Filter::make('Pagadas')
                ->query(fn (Builder $query): Builder => $query->where('payment_status', 'paid')),
                Filter::make('Pendientes')
                ->query(fn (Builder $query): Builder => $query->where('payment_status', 'pending')),
                Filter::make('Parciales')
                ->query(fn (Builder $query): Builder => $query->where('payment_status', 'partial'))
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ])
            ->recordUrl(
                fn (Model $record): string => route('filament.admin.resources.suscriptions.view', ['record' => $record])
            )
            ->groups([
                Group::make('customer.name')
                ->label('Cliente'),
            ]);
    }
}
