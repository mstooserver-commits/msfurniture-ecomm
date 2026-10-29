<?php

namespace App\Livewire\Store;

use App\Models\CartItem;
use App\Services\CartService;
use Livewire\Component;

class CartPage extends Component
{
    public string $couponCode = '';

    public function applyCoupon(CartService $cart): void
    {
        $result = $cart->applyCoupon($this->couponCode);

        if ($result['success']) {
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function updateQty(int $itemId, int $qty, CartService $cart): void
    {
        $item = CartItem::findOrFail($itemId);
        $cart->updateQuantity($item, $qty);
        $this->dispatch('cart-updated');
    }

    public function remove(int $itemId, CartService $cart): void
    {
        $cart->remove(CartItem::findOrFail($itemId));
        $this->dispatch('cart-updated');
    }

    public function render(CartService $cartService)
    {
        $cart = $cartService->getCart()->load(['items.product.images', 'items.variant']);
        $subtotal = $cart->subtotal();
        $discount = $cartService->calculateDiscount($cart, $subtotal);

        return view('livewire.store.cart-page', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => round(max(0, $subtotal - $discount) * (config('shop.tax_rate') / 100), 2),
            'shipping' => $subtotal >= config('shop.free_shipping_threshold') ? 0 : config('shop.shipping_fee'),
        ])->layout('layouts.store');
    }
}
