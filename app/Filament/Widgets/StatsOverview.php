<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Support;
use App\Models\Suscription;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use function Symfony\Component\Clock\now;

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
                ->description('+' . $thisMonthSuscriptions . ' este mes' )
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Clientes', $totalCustomers)
                ->description('+' . $thisMonthCustomers . ' este mes' )
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Soportes', $totalSupports)
                ->description('+' . $thisMonthSupports . ' este mes' )
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}
