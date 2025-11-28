<?php

namespace App\Filament\Resources\Incomes\Tables;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Support;
use App\Models\Suscription;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

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
                    ->money()
                    ->sortable()
                    ->label('Total')
                    ->weight('bold'),
                TextColumn::make('attached_file')
                    ->label('Adjunto')
                    ->state('Ver archivo')
                    // ->formatStateUsing(function ($record) {
                    //     $filePath = $record->attached_file;
                    //     return Storage::url($filePath);
                    // })
                    ->url(fn ($record) => Storage::url($record->attached_file), true) // El segundo argumento 'true' indica que se descargará
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('paymentMethod.name')
                    ->searchable()
                    ->label('F. Pago'),
                TextColumn::make('incomeable_id')
                    ->label('Cliente')
                    ->state(function($record){
                        return $record->incomeable->customer->name;
                    })
                    ->url(fn($record): string => CustomerResource::getUrl('view', ['record' => $record->incomeable->customer])),
                TextColumn::make('incomeable')
                    ->label('Recurso')
                    ->formatStateUsing(function($state){
                        $value = $state['number'];
                        return $value;
                    })
                    ->description(function($state){
                        $value = "";
                        if ($state instanceof Suscription) {
                            $value = "Suscripcion";
                        }elseif ($state instanceof Support) {
                            $value = "Soporte";
                        }

                        return $value;
                    })
                    ->url(function($state){
                            $route = ""; 
                            if ($state instanceof Suscription) {
                                $route = route('filament.admin.resources.suscriptions.view',['record' => $state]);
                            }elseif ($state instanceof Support) {
                                $route = route('filament.admin.resources.supports.view',['record' => $state]);
                            }

                            return $route;
                        }),
                TextColumn::make('description')
                    ->searchable()
                    ->label('Descripción')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                DeleteAction::make()
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
