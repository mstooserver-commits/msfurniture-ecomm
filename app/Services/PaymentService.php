<?php

namespace App\Services;

use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use Razorpay\Api\Api as RazorpayApi;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentService
{
    public function createStripeSession(Order $order, string $successUrl, string $cancelUrl): Session
    {
        Stripe::setApiKey(config('shop.stripe_secret'));

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($order->currency),
                    'product_data' => ['name' => 'Order '.$order->order_number],
                    'unit_amount' => (int) round($order->total * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl.'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
            'metadata' => ['order_id' => $order->id],
        ]);
    }

    public function createRazorpayOrder(Order $order): array
    {
        $api = new RazorpayApi(config('shop.razorpay_key'), config('shop.razorpay_secret'));

        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => (int) round($order->total * 100),
            'currency' => $order->currency,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'gateway' => PaymentGateway::Razorpay->value,
            'transaction_id' => $razorpayOrder['id'],
            'amount' => $order->total,
            'currency' => $order->currency,
            'status' => PaymentStatus::Pending->value,
            'meta' => $razorpayOrder->toArray(),
        ]);

        return [
            'order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
            'key' => config('shop.razorpay_key'),
        ];
    }

    public function markPaid(Order $order, PaymentGateway $gateway, ?string $transactionId = null): void
    {
        $order->update([
            'payment_status' => PaymentStatus::Paid,
            'paid_at' => now(),
        ]);

        Payment::updateOrCreate(
            ['order_id' => $order->id, 'gateway' => $gateway->value],
            [
                'transaction_id' => $transactionId,
                'amount' => $order->total,
                'currency' => $order->currency,
                'status' => PaymentStatus::Paid->value,
            ]
        );
    }
}
