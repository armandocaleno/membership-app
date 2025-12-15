<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Suscription;
use Carbon\Carbon;
use Filament\Tables\Filters\Filter;


class ExpiringSuscriptions extends TableWidget
{
    protected static ?int $sort = 5;
    public ?string $filter = 'one_month';
    

    public function table(Table $table): Table
    {
        $check_date = Carbon::now()->addMonth();
        // $check_date = $this->getDateRange();
        return $table
            ->query(fn (): Builder => Suscription::query()
                        ->where('status', 'active')
                        ->where('end_date', '<=', $check_date))
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
                Filter::make('1 mes')
                    ->query(fn (Builder $query): Builder => $query->orwhere('end_date','<=', Carbon::now()->addMonth())),
                Filter::make('3 meses')
                    ->query(fn (Builder $query): Builder => $query->orwhere('end_date','<=', Carbon::now()->addMonths(3))),
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
            ]);
    }

    protected function getTableHeading(): string
    {
        return 'Suscripciones por expirar'; // Aquí va el nuevo título
    }
}
