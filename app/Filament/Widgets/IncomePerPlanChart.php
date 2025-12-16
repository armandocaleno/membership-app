<?php

namespace App\Filament\Widgets;

use App\Models\Plan;
use App\Models\Suscription;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class IncomePerPlanChart extends ChartWidget
{
    protected ?string $heading = 'Planes vendidos';
    protected static ?int $sort = 4;
    public ?string $filter = 'this_year';

    protected function getData(): array
    {
        $dateRage = $this->getDateRange();

        $plans = Plan::where('status', 'active')->get();

        $labels = [];
        $totals = [];
        $suscriptionsPerPlan = Suscription::query()
                        ->join('plans', 'suscriptions.plan_id', '=', 'plans.id')
                        ->selectRaw('COUNT(*) as tot, plan_id')
                        ->whereBetween('start_date', [$dateRage['start'], $dateRage['end']])
                        ->groupBy('plan_id')
                        ->get()
                        ->keyBy('plan_id');

        foreach ($plans as $key => $plan) {
            $totals[] =  $suscriptionsPerPlan->get($plan->id)?->tot ?? 0;
            $labels[] = $plan->name;
        }
        return [
            'datasets' => [
                [
                    'label' => 'Planes vendidos',
                    'data' => $totals,
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
        return 'doughnut';
    }

    public function getDescription(): ?string
    {
        return 'Total de planes vendidos en el año.';
    }

    protected function getFilters(): ?array
    {
        return [
            'this_month' => 'Este mes',
            'this_year' => 'Este año',
            'last_year' => 'Año anterior',
        ];
    }

    private function getDateRange() : array {
        return match($this->filter){
            'this_month' => [
                'start' => now()->startOfMonth(),
                'end' => now()
            ],
            'this_year' => [
                'start' => now()->startOfYear(),
                'end' => now()
            ],
            'last_year' => [
                'start' => now()->subYear()->startOfMonth(),
                'end' => now()->subYear()->endOfMonth()
            ],
            default => [
                'start' => now()->startOfYear(),
                'end' => now()
            ]
        };
    }
}
