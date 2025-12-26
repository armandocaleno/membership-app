<?php

namespace App\Filament\Resources\Incomes;

use App\Filament\Resources\Incomes\Pages\CreateIncome;
use App\Filament\Resources\Incomes\Pages\EditIncome;
use App\Filament\Resources\Incomes\Pages\ListIncomes;
use App\Filament\Resources\Incomes\Pages\ViewIncome;
use App\Filament\Resources\Incomes\Schemas\IncomeForm;
use App\Filament\Resources\Incomes\Schemas\IncomeInfolist;
use App\Filament\Resources\Incomes\Tables\IncomesTable;
use App\Models\Income;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentCurrencyDollar;

    protected static ?string $recordTitleAttribute = 'Income';

    public static function form(Schema $schema): Schema
    {
        return IncomeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IncomeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncomesTable::configure($table);
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
            'index' => ListIncomes::route('/'),
            'create' => CreateIncome::route('/{id?}/{model?}/create'),
            'view' => ViewIncome::route('/{record}'),
            'edit' => EditIncome::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return "Ingreso";
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()
    //         ->join('suscriptions as sus', 'incomes.incomeable_id', '=', 'sus.id')
    //         ->selectRaw('incomes.*, sus.customer_id, sus.number as suscription_number');
    // }
}
