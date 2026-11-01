<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function __construct(
        protected CartService $cartService,
    ) {}

    public function calculateTotals(): array
    {
        $cart = $this->cartService->getCart()->load(['items.product', 'items.variant']);
        $subtotal = $cart->subtotal();
        $discount = $this->cartService->calculateDiscount($cart, $subtotal);
        $taxRate = (float) config('shop.tax_rate', 10);
        $taxable = max(0, $subtotal - $discount);
        $tax = round($taxable * ($taxRate / 100), 2);
        $shipping = $subtotal >= config('shop.free_shipping_threshold', 150) ? 0 : (float) config('shop.shipping_fee', 12.95);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => max(0, $subtotal - $discount + $tax + $shipping),
            'cart' => $cart,
        ];
    }

    public function placeOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $totals = $this->calculateTotals();
            $cart = $totals['cart'];

            if ($cart->items->isEmpty()) {
                throw new \RuntimeException('Cart is empty.');
            }

            $walletUsed = 0;
            $user = Auth::user();

            if ($user && ($data['use_wallet'] ?? false)) {
                $walletUsed = min((float) $user->wallet_balance, $totals['total']);
            }

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user?->id,
                'guest_email' => $data['guest_email'] ?? null,
                'status' => OrderStatus::Pending,
                'payment_status' => PaymentStatus::Pending,
                'payment_method' => $data['payment_method'] ?? PaymentGateway::Cod->value,
                'currency' => $data['currency'] ?? config('shop.currency', 'INR'),
                'subtotal' => $totals['subtotal'],
                'discount_amount' => $totals['discount'],
                'tax_amount' => $totals['tax'],
                'shipping_amount' => $totals['shipping'],
                'wallet_used' => $walletUsed,
                'total' => $totals['total'] - $walletUsed,
                'coupon_code' => $cart->coupon_code,
                'shipping_address' => $data['shipping_address'],
                'billing_address' => $data['billing_address'] ?? $data['shipping_address'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'variant_label' => $item->variant
                        ? trim(($item->variant->size ?? '').' '.($item->variant->color ?? ''))
                        : null,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->lineTotal(),
                ]);

                $item->product->increment('sales_count', $item->quantity);
                $item->product->decrement('stock', $item->quantity);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => OrderStatus::Pending->value,
                'note' => 'Order placed',
                'created_by' => $user?->id,
            ]);

            if ($cart->coupon_code) {
                Coupon::where('code', $cart->coupon_code)->increment('used_count');
            }

            $cart->items()->delete();
            $cart->update(['coupon_code' => null]);

            return $order;
        });
    }
}
