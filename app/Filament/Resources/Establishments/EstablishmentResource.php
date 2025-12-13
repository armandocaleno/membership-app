<?php

namespace App\Filament\Resources\Establishments;

use App\Filament\Resources\Establishments\Pages\CreateEstablishment;
use App\Filament\Resources\Establishments\Pages\EditEstablishment;
use App\Filament\Resources\Establishments\Pages\ListEstablishments;
use App\Filament\Resources\Establishments\Schemas\EstablishmentForm;
use App\Filament\Resources\Establishments\Tables\EstablishmentsTable;
use App\Models\Establishment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EstablishmentResource extends Resource
{
    protected static ?string $model = Establishment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::HomeModern;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return EstablishmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstablishmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEstablishments::route('/'),
            'create' => CreateEstablishment::route('/{customer?}/create/'),
            'edit' => EditEstablishment::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return "Establecimiento";
    }
}
