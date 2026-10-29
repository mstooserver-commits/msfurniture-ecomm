<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items', 'statusHistory', 'payments']);

        return view('orders.show', compact('order'));
    }

    public function success(Order $order, Request $request, PaymentService $payment)
    {
        if ($request->session_id && config('shop.stripe_secret')) {
            $payment->markPaid($order, \App\Enums\PaymentGateway::Stripe, $request->session_id);
            $order->update(['status' => \App\Enums\OrderStatus::Confirmed]);
        }

        return view('orders.success', compact('order'));
    }
}
