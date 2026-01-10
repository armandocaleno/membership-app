<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DevicesRelationManager extends RelationManager
{
    protected static string $relationship = 'devices';
     protected static ?string $title = 'Dispositivos';

    public static function getModelLabel(): string
    {
        return "Dispositivos";
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('serial')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->default(null)
                    ->label('Descripción'),
                Select::make('device_type_id')
                    ->relationship('deviceType', 'name')
                    ->required()
                    ->label('Tipo')
                    ->native(false),
                Select::make('establishment_id')
                    ->required()
                    ->default(null)
                    ->options(fn () =>  $this->getOwnerRecord()->establishments->pluck('name', 'id'))
                    ->label('Establecimiento')
                    ->reactive()
                    ->native(false),
                Textarea::make('notes')
                    ->label('notas')
                    ->autosize()
                    ->rows(1),
                Repeater::make('remoteDesktopSoftware')
                    ->schema([
                        TextInput::make('conecction_id')
                            ->label('Id conexión'),
                        TextInput::make('name')
                            ->label('Software'),
                    ])
                    ->addActionLabel('Añadir Software Remoto')
                    ->maxItems(3)
                    ->columns(2)
                    ->columnSpan('full')
                    ->label('Software de Escritorio Remoto')
                    ->defaultItems(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('serial')
            ->columns([
                TextColumn::make('serial')
                    ->searchable()
                    ->label('No. de Serie'),
                TextColumn::make('description')
                    ->searchable()
                    ->label('Descripción'),
                TextColumn::make('deviceType.name')
                    ->sortable()
                    ->label('Tipo'),
                TextColumn::make('establishment.name')
                    ->searchable()
                    ->label('Establecimiento'),
                TextColumn::make('remoteDesktopSoftware')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->label('Software Remoto')
                    ->formatStateUsing(fn (array $state): string => implode(' -> ', $state)),
                TextColumn::make('notes')
                    ->label('Notas')
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
