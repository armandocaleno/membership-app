<?php

namespace App\Filament\Widgets;

use App\Models\Income;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class IncomePerWeekChart extends ChartWidget
{
    protected ?string $heading = 'Ingresos por día';
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
        $start = now()->startOfWeek()->translatedFormat('M j');
        $end = now()->endOfWeek()->translatedFormat('M j');
        $this_week = $start . ' - ' . $end;

        $last_start = now()->subWeek()->startOfWeek()->translatedFormat('M j');
        $last_end = now()->subWeek()->endOfWeek()->translatedFormat('M j');
        $last_week = $last_start . ' - ' . $last_end;

        $two_week_start = now()->subWeek(2)->startOfWeek()->translatedFormat('M j');
        $two_week_end = now()->subWeek(2)->endOfWeek()->translatedFormat('M j');
        $last_2_week = $two_week_start . ' - ' . $two_week_end;

        $three_week_start = now()->subWeek(3)->startOfWeek()->translatedFormat('M j');
        $three_week_end = now()->subWeek(3)->endOfWeek()->translatedFormat('M j');
        $last_3_week = $three_week_start . ' - ' . $three_week_end;

        return [
            'this_week' => $this_week,
            'last_week' => $last_week,
            'last_2_week' => $last_2_week,
            'last_3_week' => $last_3_week,
        ];
    }

    private function getDateRange() : array {
        return match($this->filter){
            'this_week' => [
                'start' => now()->startOfWeek(),
                'end' => now()
            ],
            'last_week' => [
                'start' => now()->subWeek()->startOfWeek(),
                'end' => now()->subWeek()->endOfWeek()
            ],
            'last_2_week' => [
                'start' => now()->subWeek(2)->startOfWeek(),
                'end' => now()->subWeek(2)->endOfWeek()
            ],
            'last_3_week' => [
                'start' => now()->subWeek(3)->startOfWeek(),
                'end' => now()->subWeek(3)->endOfWeek()
            ],
            default => [
                'start' => now()->startOfWeek(),
                'end' => now()
            ]
        };
    }
}
