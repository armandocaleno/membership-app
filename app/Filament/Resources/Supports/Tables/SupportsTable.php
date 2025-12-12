<?php

namespace App\Filament\Resources\Supports\Tables;

use App\Filament\Resources\Incomes\IncomeResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
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
                    ->searchable(),
                TextColumn::make('establishment.name')
                    ->label('Establecimiento')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('device.name')
                    ->label('Dispositivo')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total')
                    ->money()
                    ->label('Total')
                    ->weight('bold'),
                TextColumn::make('incomes')
                    ->label('Pagos')
                    ->listWithLineBreaks()
                    ->formatStateUsing(fn($state):string => '$ ' . $state->total)
                    ->alignment('right')
                    ->url(fn($state):string =>IncomeResource::getUrl('view', ['record' => $state]))
                    ->placeholder('0.00'),
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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
                fn (Model $record): string => route('filament.admin.resources.supports.view', ['record' => $record])
            )
            ->groups([
                Group::make('customer.name')
                ->label('Cliente'),
            ]);
    }
}
