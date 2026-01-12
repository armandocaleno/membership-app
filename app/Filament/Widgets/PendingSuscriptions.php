<?php

namespace App\Filament\Widgets;

use App\Models\Suscription;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PendingSuscriptions extends TableWidget
{
    protected static ?int $sort = 9;
    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Suscription::query()
                        ->where('payment_status','!=', 'paid')
                        ->where('status', 'active'))
            ->columns([
                TextColumn::make('number')
                    ->label('Número'),
                TextColumn::make('start_date')
                    ->label('Inicio')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('Fin')
                    ->date(),
                TextColumn::make('customer.name')
                    ->label('Cliente'),
                TextColumn::make('plan.name'),
                TextColumn::make('incomes.total')
                    ->label('Pagos')
                    ->listWithLineBreaks()
                    ->alignment('right')
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
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->recordUrl(
                fn (Model $record): string => route('filament.admin.resources.suscriptions.view', ['record' => $record])
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }

    protected function getTableHeading(): string
    {
        return 'Suscripciones pendientes de cobro'; // Aquí va el nuevo título
    }

    public static function canView(): bool
    {
        return auth()->user()->can('View:PendingSuscriptions');
    }
}
