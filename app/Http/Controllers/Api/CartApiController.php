<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    public function show(CartService $cart)
    {
        return response()->json($cart->getCart()->load(['items.product.images', 'items.variant']));
    }

    public function store(Request $request, CartService $cart)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? $product->variants()->find($request->variant_id) : null;

        $item = $cart->add($product, $variant, $request->quantity ?? 1);

        return response()->json($item->load('product'), 201);
    }

    public function update(CartItem $item, Request $request, CartService $cart)
    {
        $cart->updateQuantity($item, $request->validate(['quantity' => 'required|integer|min:0'])['quantity']);

        return response()->json(['message' => 'Updated']);
    }

    public function destroy(CartItem $item, CartService $cart)
    {
        $cart->remove($item);

        return response()->json(['message' => 'Removed']);
    }
}
