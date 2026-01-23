<?php

namespace App\Filament\Widgets;

use App\Models\Plan;
use App\Models\Suscription;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class SuscriptionPerPlanChart extends ChartWidget
{
    protected ?string $heading = 'Ingresos por plan';
    protected static ?int $sort = 5;
    public ?string $filter = 'this_year';

    protected function getData(): array
    {
        $dateRange = $this->getDateRange();

        $plans = Plan::where('status', 'active')->get();

        $labels = [];
        $totals = [];
        
        $suscriptionsPerPlan = Suscription::query()->select('plan_id')
                                ->withSum('incomes as total', 'total')
                                ->join('incomes', 'suscriptions.id', '=', 'incomes.incomeable_id')
                                ->whereBetween('incomes.date', [$dateRange['start'], $dateRange['end']])
                                ->groupBy('plan_id', 'suscriptions.id')
                                ->get()->toArray();

        $resultado = [];
       foreach ($suscriptionsPerPlan as $item) {
            $planId = $item['plan_id'];
            $total = $item['total'];

            if (!isset($resultado[$planId])) {
                $resultado[$planId] = 0;
            }
            $resultado[$planId] += $total;
        }

        foreach ($plans as $plan) {
            $totals[] =  $resultado[$plan->id] ?? 0;
            $labels[] = $plan->name;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total de ingresos',
                    'data' => $totals,
                    'backgroundColor' => [
                        Color::Sky['900'],
                        Color::Orange['400'],
                        Color::Teal['600'],
                        Color::Amber['300'],
                        Color::Red['600'],
                        Color::Zinc['400'],
                        Color::Blue['600'],
                    ]
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    public function getDescription(): ?string
    {
        return 'Total de ingresos por plan.';
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
        return auth()->user()->can('View:SuscriptionPerPlanChart');
    }
}
