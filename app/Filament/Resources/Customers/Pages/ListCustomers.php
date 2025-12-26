<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected ?string $heading = 'Lista de clientes';  

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->createAnother(false),
        ];
    }
}
