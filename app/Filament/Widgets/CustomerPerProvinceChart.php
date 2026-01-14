<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class CustomerPerProvinceChart extends ChartWidget
{
    protected ?string $heading = 'Clientes por provincia';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 1;

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

        $provinces = Customer::getProvinces();
        $customers = Customer::query()
                        ->where('status', 'active')
                        ->selectRaw('COUNT(*) as totalCustomer, province')
                        ->groupBy('province')
                        ->get()
                        ->keyBy('province');
        foreach ($provinces as $province) {
            if ($customers->get($province)) {
                $totals[] =  $customers->get($province)->totalCustomer;
                $labels[] = $province;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total clientes',
                    'data' => $totals,
                    // 'borderColor' => '#9BD0F5',
                    'backgroundColor' => [
                        Color::Sky['900'],
                    ]
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return auth()->user()->can('View:CustomerPerProvinceChart');
    }
}
