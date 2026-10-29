<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders (last 7 days)';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = collect(range(6, 0))->map(function (int $daysAgo) {
            $date = now()->subDays($daysAgo)->startOfDay();

            return [
                'date' => $date->format('D'),
                'count' => Order::whereDate('created_at', $date)->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => '#18181b',
                    'backgroundColor' => 'rgba(24, 24, 27, 0.08)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
