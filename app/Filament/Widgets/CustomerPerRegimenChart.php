<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Regime;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class CustomerPerRegimenChart extends ChartWidget
{
    protected ?string $heading = 'Clientes por régimen SRI';
    protected static ?int $sort = 7;

    protected ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];

    protected function getData(): array
    {
        $labels = [];
        $totals = [];

        $regimes = Regime::all();
        $customers = Customer::query()
                        ->where('status', 'active')
                        ->selectRaw('COUNT(*) as totalCustomer, regime_id')
                        ->groupBy('regime_id')
                        ->get()
                        ->keyBy('regime_id');

        foreach ($regimes as $regime) {
            $totals[] = $customers->get($regime->id)?->totalCustomer ?? 0;
            $labels[] = $regime->name;  
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total clientes',
                    'data' => $totals,
                    // 'borderColor' => '#9BD0F5',
                    'backgroundColor' => [
                        Color::Sky['900'],
                        Color::Orange['400'],
                        Color::Teal['600']
                    ]
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }

    public static function canView(): bool
    {
        return auth()->user()->can('View:CustomerPerRegimenChart');
    }
}
