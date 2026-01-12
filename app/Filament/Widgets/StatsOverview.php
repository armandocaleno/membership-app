<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Support;
use App\Models\Suscription;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Rendimiento';

    protected ?string $description = 'Rendimiento del último mes';

    protected function getStats(): array
    {
        $totalCustomers = Customer::where('status', 'active')->count();
        $totalSuscriptions = Suscription::where('status', 'active')->count();
        $totalSupports = Support::count();
        $thisMonth = \Carbon\Carbon::now()->startOfMonth();

        $thisMonthSuscriptions = Suscription::forDateRange($thisMonth, now())->count();
        $thisMonthCustomers = Customer::forDateRange($thisMonth, now())->count();
        $thisMonthSupports = Support::forDateRange($thisMonth, now())->count();
        
        return [
            Stat::make('Suscripciones', $totalSuscriptions)
                ->icon('heroicon-s-calendar')
                ->description('+' . $thisMonthSuscriptions . ' este mes' )
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->extraAttributes([
                    'wire:navigate' => 'true', // Para navegar a una página de recurso de Filament
                    'href' => route('filament.admin.resources.suscriptions.create'), // URL de destino
                    'class' => 'cursor-pointer', // Estilo opcional
                ]),
            Stat::make('Clientes', $totalCustomers)
                ->icon('heroicon-s-users')
                ->description('+' . $thisMonthCustomers . ' este mes' )
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->extraAttributes([
                    'wire:navigate' => 'true', // Para navegar a una página de recurso de Filament
                    'href' => route('filament.admin.resources.customers.create'), // URL de destino
                    'class' => 'cursor-pointer', // Estilo opcional
                ]),
            Stat::make('Soportes', $totalSupports)
                ->icon('heroicon-s-wrench-screwdriver')
                ->description('+' . $thisMonthSupports . ' este mes' )
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->extraAttributes([
                    'wire:navigate' => 'true', // Para navegar a una página de recurso de Filament
                    'href' => route('filament.admin.resources.supports.create'), // URL de destino
                    'class' => 'cursor-pointer', // Estilo opcional
                ])
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->can('View:StatsOverview');
    }
}
