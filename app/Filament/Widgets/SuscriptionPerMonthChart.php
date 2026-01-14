<?php

namespace App\Filament\Widgets;

use App\Models\Suscription;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class SuscriptionPerMonthChart extends ChartWidget
{
    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = '300px';
    protected ?string $heading = 'Suscripciones por mes';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $dateRange = $this->getDateRange();

        $suscriptionsPerMonth = Suscription::query()->with('plan')
            ->whereBetween('start_date', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('MONTH(start_date) as month, COUNT(*) as totalSuscriptions')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        $labels = [];
        $totals = [];

        for ($date = $dateRange['start']->copy(); $date <= $dateRange['end'] ; $date->addMonth()) { 
            $dateString = $date->format('n');
            $labels[] = $date->locale('es')->isoFormat('MMM');
            $totals[] = $suscriptionsPerMonth->get($dateString) ?->totalSuscriptions ?? 0; 
        }

        return [
            'datasets' => [
                [
                    'label' => 'Suscripciones del mes',
                    'data' => $totals,
                    'backgroundColor' => [
                        Color::Sky['900'],
                        Color::Orange['400'],
                        Color::Teal['600'],
                        Color::Amber['300'],
                        Color::Red['600'],
                        Color::Zinc['400'],
                        
                    ],
                    'borderWidth'=> 0
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true
                ]
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            'this_year' => 'Esta año',
            'last_year' => now()->subYear()->format('Y'),
            'last_2_year' => now()->subYear(2)->format('Y'),
            'last_3_year' => now()->subYear(3)->format('Y'),
            'last_4_year' => now()->subYear(4)->format('Y'),
        ];
    }

    private function getDateRange() : array {
        return match($this->filter){
            'this_year' => [
                'start' => now()->startOfYear(),
                'end' => now()
            ],
            'last_year' => [
                'start' => now()->subYear()->startOfYear(),
                'end' => now()->subYear()->endOfYear()
            ],
            'last_2_year' => [
                'start' => now()->subYear(2)->startOfYear(),
                'end' => now()->subYear(2)->endOfYear()
            ],
            'last_3_year' => [
                'start' => now()->subYear(3)->startOfYear(),
                'end' => now()->subYear(3)->endOfYear()
            ],
            'last_4_year' => [
                'start' => now()->subYear(4)->startOfYear(),
                'end' => now()->subYear(4)->endOfYear()
            ],
            default => [
                'start' => now()->startOfYear(),
                'end' => now()
            ]
        };
    }

    // public static function canView(): bool
    // {
    //     return auth()->user()->can('View:SuscriptionPerMonthChart');
    // }
}
