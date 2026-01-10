<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Models\Customer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EstablishmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'establishments';
    protected static ?string $title = 'Establecimientos';

    public static function getModelLabel(): string
    {
        return "Establecimientos";
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Código')
                    ->required()
                    ->maxLength(10),
                TextInput::make('name')
                    ->label('Nombre'),
                TextInput::make('address')
                    ->default(null)
                    ->label('Dirección'),
                Select::make('province')
                    ->options(Customer::getProvinces())
                    ->label('Provincia')
                    ->native(false),
                TextInput::make('city')
                    ->label('Ciudad')
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null)
                    ->label('Teléfono'),
                Select::make('status')
                    ->options(['active' => 'Activo', 'inactive' => 'Inactivo'])
                    ->default('active')
                    ->required()
                    ->label('Estado')
                    ->native(false)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('address')
                    ->searchable()
                    ->label('Dirección'),
                TextColumn::make('province')
                    ->label('Provincia')
                    ->default('-'),
                TextColumn::make('city')
                    ->label('Ciudad')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('Teléfono'),
                TextColumn::make('status')
                    ->badge()
                    ->label('Estado')
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, $record) {
                        if ($record->devices()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este establecimiento porque tiene dispositivos relacionadas.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }
                    }),
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
