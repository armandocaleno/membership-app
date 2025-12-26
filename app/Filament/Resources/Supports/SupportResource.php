<?php

namespace App\Filament\Resources\Supports;

use App\Filament\Resources\Supports\Pages\CreateSupport;
use App\Filament\Resources\Supports\Pages\EditSupport;
use App\Filament\Resources\Supports\Pages\ListSupports;
use App\Filament\Resources\Supports\Pages\ViewSupport;
use App\Filament\Resources\Supports\Schemas\SupportForm;
use App\Filament\Resources\Supports\Schemas\SupportInfolist;
use App\Filament\Resources\Supports\Tables\SupportsTable;
use App\Models\Support;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SupportResource extends Resource
{
    protected static ?string $model = Support::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog;

    protected static ?string $recordTitleAttribute = 'Support';

    public static function form(Schema $schema): Schema
    {
        return SupportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupportsTable::configure($table);
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
            'index' => ListSupports::route('/'),
            'create' => CreateSupport::route('/create'),
            'view' => ViewSupport::route('/{record}'),
            'edit' => EditSupport::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return "Soporte";
    }
}
