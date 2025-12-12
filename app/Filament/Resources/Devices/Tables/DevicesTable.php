<?php

namespace App\Filament\Resources\Devices\Tables;

use App\Models\DeviceType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class DevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('serial')
                    ->searchable()
                    ->label('No. de Serie'),
                TextColumn::make('description')
                    ->searchable()
                    ->label('Descripción'),
                TextColumn::make('deviceType.name')
                    // ->getRelationship(DeviceType, 'type')
                    ->sortable()
                    ->label('Tipo'),
                TextColumn::make('establishment.name')
                    ->searchable()
                    ->label('Establecimiento'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Creado'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Modificado'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ])
            ->groups([
                Group::make('establishment.name')
                ->label('Establecimiento'),
            ]);
    }
}
