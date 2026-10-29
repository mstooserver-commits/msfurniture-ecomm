<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->orders()->with('items')->latest()->paginate(10)
        );
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return response()->json($order->load(['items', 'statusHistory', 'payments']));
    }

    public function store(Request $request, CheckoutService $checkout)
    {
        $request->validate([
            'shipping_address' => 'required|array',
            'payment_method' => 'required|string',
        ]);

        $order = $checkout->placeOrder($request->only(['shipping_address', 'billing_address', 'payment_method', 'notes']));

        return response()->json($order->load('items'), 201);
    }
}
