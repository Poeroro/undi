<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class UserGrowthChart extends ChartWidget
{
    protected ?string $heading = 'Pertumbuhan user';

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(fn (int $offset) => now()->subMonths($offset)->startOfMonth());

        return [
            'datasets' => [
                [
                    'label' => 'User baru',
                    'data' => $months->map(fn (Carbon $month) => User::query()
                        ->whereBetween('created_at', [$month, $month->copy()->endOfMonth()])
                        ->count())->all(),
                ],
            ],
            'labels' => $months->map(fn (Carbon $month) => $month->translatedFormat('M Y'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
