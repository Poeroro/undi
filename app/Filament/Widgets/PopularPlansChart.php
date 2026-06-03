<?php

namespace App\Filament\Widgets;

use App\Models\Plan;
use Filament\Widgets\ChartWidget;

class PopularPlansChart extends ChartWidget
{
    protected ?string $heading = 'Paket terlaris';

    protected function getData(): array
    {
        $plans = Plan::query()
            ->withCount('subscriptions')
            ->orderByDesc('subscriptions_count')
            ->take(6)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Subscription',
                    'data' => $plans->pluck('subscriptions_count')->all(),
                ],
            ],
            'labels' => $plans->pluck('name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
