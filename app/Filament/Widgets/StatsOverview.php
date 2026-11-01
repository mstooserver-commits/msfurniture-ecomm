<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $revenue = Order::where('payment_status', PaymentStatus::Paid)->sum('total');
        $ordersToday = Order::whereDate('created_at', today())->count();
        $lowStock = Product::whereColumn('stock', '<=', 'low_stock_threshold')->count();

        return [
            Stat::make('Revenue', '₹'.number_format($revenue, 2))
                ->description('Paid orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([7, 12, 8, 15, 10, 18, $ordersToday]),
            Stat::make('Orders today', $ordersToday)
                ->description('New orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),
            Stat::make('Products', Product::count())
                ->description($lowStock.' low stock')
                ->descriptionIcon('heroicon-m-cube')
                ->color($lowStock > 0 ? 'warning' : 'success'),
            Stat::make('Customers', User::where('is_admin', false)->count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),
        ];
    }
}
