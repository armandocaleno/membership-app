<?php

namespace App\Filament\Widgets;

use App\Models\Income;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class IncomePerWeekChart extends ChartWidget
{
    protected ?string $heading = 'Ingresos por mes';
    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = '300px';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $dateRange = $this->getDateRange();
        
        $incomePerMonth = Income::query()
            ->whereBetween('date', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('DATE(date) as date, SUM(total) as totalMonth')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        
        $labels = [];
        $totals = [];

        for ($date = $dateRange['start']->copy(); $date <= $dateRange['end'] ; $date->addDay()) { 
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->locale('es')->translatedFormat('M j');
            $totals[] = $incomePerMonth->get($dateString) ?->totalMonth ?? 0; 
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos diarios',
                    'data' => $totals,
                    'borderColor' => Color::Orange['600'],
                    'backgroundColor' => Color::Orange['200'],
                    'fill' => true,
                    'tension' => 0.3
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
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'this_month' => 'Este mes',
            'last_month' => 'Mes anterior',
        ];
    }

    private function getDateRange() : array {
        return match($this->filter){
            'this_month' => [
                'start' => now()->startOfMonth(),
                'end' => now()
            ],
            'last_month' => [
                'start' => now()->subMonth()->startOfMonth(),
                'end' => now()->subMonth()->endOfMonth()
            ],
            default => [
                'start' => now()->startOfMonth(),
                'end' => now()
            ]
        };
    }
}
