<?php

namespace App\Filament\Widgets;

use App\Models\Income;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class IncomePerMonthChart extends ChartWidget
{
    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = '300px';
    protected ?string $heading = 'Ingresos por mes';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $dateRange = $this->getDateRange();
        
        $incomePerMonth = Income::query()
            ->whereBetween('date', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('MONTH(date) as month, SUM(total) as totalMonth')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        $labels = [];
        $totals = [];

        for ($date = $dateRange['start']->copy(); $date <= $dateRange['end'] ; $date->addMonth()) { 
            $dateString = $date->format('n');
            $labels[] = $date->locale('es')->isoFormat('MMM');
            $totals[] = $incomePerMonth->get($dateString) ?->totalMonth ?? 0; 
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos del mes',
                    'data' => $totals,
                    'borderColor' => Color::Sky['900']
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
            'this_year' => 'Este año',
            'last_year' => 'Año anterior',
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
            default => [
                'start' => now()->startOfYear(),
                'end' => now()
            ]
        };
    }

    public static function canView(): bool
    {
        return auth()->user()->can('View:IncomePerMonthChart');
    }
}
