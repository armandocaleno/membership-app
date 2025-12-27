<?php

namespace App\Filament\Resources\Regimes\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nombre'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ]);
    }
}
