<?php

namespace App\Filament\Widgets;

use App\Models\Income;
use App\Models\Plan;
use App\Models\Suscription;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class SuscriptionPerPlanChart extends ChartWidget
{
    protected ?string $heading = 'Ingresos por plan';
    protected static ?int $sort = 4;
    public ?string $filter = 'this_year';

    protected function getData(): array
    {
        $dateRange = $this->getDateRange();

        $plans = Plan::where('status', 'active')->get();

        $labels = [];
        $totals = [];
        // $suscriptionsPerPlan = Suscription::query()
        //                 ->join('plans', 'suscriptions.plan_id', '=', 'plans.id')
        //                 ->selectRaw('SUM(plans.price) as totalPlan, plan_id')
        //                 ->whereBetween('start_date', [$dateRange['start'], $dateRange['end']])
        //                 ->groupBy('plan_id')
        //                 ->get()
        //                 ->keyBy('plan_id');
        $suscriptionsPerPlan = Income::query()
                        ->rightjoin('suscriptions', 'incomes.incomeable_id', '=', 'suscriptions.id')
                        ->join('plans', 'suscriptions.plan_id', '=', 'plans.id')
                        ->selectRaw('SUM(plans.price) as totalPlan, plan_id, suscriptions.number')
                        ->whereBetween('incomes.date', [$dateRange['start'], $dateRange['end']])
                        ->where('incomes.incomeable_type', Suscription::class)
                        ->groupBy('plan_id', 'suscriptions.number')
                        ->get()
                        ->keyBy('plan_id');

        foreach ($plans as $plan) {
            $totals[] =  $suscriptionsPerPlan->get($plan->id)?->totalPlan ?? 0;
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
