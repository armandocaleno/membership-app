<?php

namespace App\Filament\Resources\Suscriptions;

use App\Filament\Resources\Suscriptions\Pages\CreateSuscription;
use App\Filament\Resources\Suscriptions\Pages\EditSuscription;
use App\Filament\Resources\Suscriptions\Pages\ListSuscriptions;
use App\Filament\Resources\Suscriptions\Pages\ViewSuscription;
use App\Filament\Resources\Suscriptions\Schemas\SuscriptionForm;
use App\Filament\Resources\Suscriptions\Schemas\SuscriptionInfolist;
use App\Filament\Resources\Suscriptions\Tables\SuscriptionsTable;
use App\Models\Suscription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuscriptionResource extends Resource
{
    protected static ?string $model = Suscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDateRange;

    protected static ?string $recordTitleAttribute = 'Suscription';
    protected static ?string $pluralModelLabel = 'Suscripciones';

    public static function form(Schema $schema): Schema
    {
        return SuscriptionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SuscriptionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuscriptionsTable::configure($table);
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
            'index' => ListSuscriptions::route('/'),
            'create' => CreateSuscription::route('/create'),
            'view' => ViewSuscription::route('/{record}'),
            'edit' => EditSuscription::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return "Suscripción";
    }
}
