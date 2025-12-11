<?php

namespace App\Filament\Resources\Customers\Tables;

use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
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
                Filter::make('Activos')
                ->query(fn (Builder $query): Builder => $query->where('status', 'active')),
                Filter::make('Inactivos')
                ->query(fn (Builder $query): Builder => $query->where('status', 'inactive')),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                
            ]);
    }
}
