<?php

namespace App\Filament\Resources\Incomes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IncomesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->searchable()
                    ->label('Número'),
                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->label('Fecha'),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable()
                    ->label('Total'),
                TextColumn::make('attached_file')
                    ->searchable()
                    ->label('Adjunto'),
                TextColumn::make('paymentMethod.name')
                    ->searchable()
                    ->label('F. Pago'),
                TextColumn::make('incomeable_type')
                    ->searchable(),
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
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
