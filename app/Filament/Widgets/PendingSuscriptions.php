<?php

namespace App\Filament\Widgets;

use App\Models\Suscription;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingSuscriptions extends TableWidget
{
    protected static ?int $sort = 7;

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
                TextColumn::make('plan.name')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ])
            ->paginated([5, 10]);
    }

    protected function getTableHeading(): string
    {
        return 'Suscripciones pendientes de cobro'; // Aquí va el nuevo título
    }
}
