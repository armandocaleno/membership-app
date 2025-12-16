<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Income;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class IncomePerMonthChart extends ChartWidget
{
    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = '300px';
    protected ?string $heading = 'Ingresos por mes';
    protected static ?int $sort = 2;
  
    protected ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];

    protected function getData(): array
    {
        $startDate = now()->startOfYear();
        $endDate = now();
        
        $incomePerMonth = Income::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('MONTH(date) as month, SUM(total) as totalMonth')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        $labels = [];
        $totals = [];

        for ($date = $startDate->copy(); $date <= $endDate ; $date->addMonth()) { 
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

    protected function getType(): string
    {
        return 'line';
    }
}
