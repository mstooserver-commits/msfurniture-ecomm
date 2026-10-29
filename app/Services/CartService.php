<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart(): Cart
    {
        if ($user = Auth::user()) {
            return Cart::firstOrCreate(['user_id' => $user->id]);
        }

        $sessionId = Session::getId();

        return Cart::firstOrCreate(['session_id' => $sessionId], ['user_id' => null]);
    }

    public function add(Product $product, ?ProductVariant $variant = null, int $quantity = 1): CartItem
    {
        $cart = $this->getCart();
        $unitPrice = $variant?->price ?? $product->effectivePrice();

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant?->id)
            ->first();

        if ($item) {
            $item->update([
                'quantity' => $item->quantity + $quantity,
                'unit_price' => $unitPrice,
            ]);

            return $item->fresh();
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
        ]);
    }

    public function updateQuantity(CartItem $item, int $quantity): void
    {
        if ($quantity <= 0) {
            $item->delete();

            return;
        }

        $item->update(['quantity' => $quantity]);
    }

    public function remove(CartItem $item): void
    {
        $item->delete();
    }

    public function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();

        if (! $coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code.'];
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return ['success' => false, 'message' => 'Coupon has expired.'];
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return ['success' => false, 'message' => 'Coupon usage limit reached.'];
        }

        $cart = $this->getCart();
        $cart->update(['coupon_code' => $code]);

        return ['success' => true, 'message' => 'Coupon applied successfully.', 'coupon' => $coupon];
    }

    public function calculateDiscount(Cart $cart, float $subtotal): float
    {
        if (! $cart->coupon_code) {
            return 0;
        }

        $coupon = Coupon::where('code', $cart->coupon_code)->where('is_active', true)->first();

        if (! $coupon) {
            return 0;
        }

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return 0;
        }

        return match ($coupon->type) {
            'percentage' => round($subtotal * ($coupon->value / 100), 2),
            'fixed' => min((float) $coupon->value, $subtotal),
            default => 0,
        };
    }

    public function mergeGuestCart(User $user): void
    {
        $guestCart = Cart::where('session_id', Session::getId())->whereNull('user_id')->first();

        if (! $guestCart || $guestCart->items->isEmpty()) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($guestCart->items as $item) {
            $existing = $userCart->items()
                ->where('product_id', $item->product_id)
                ->where('product_variant_id', $item->product_variant_id)
                ->first();

            if ($existing) {
                $existing->update(['quantity' => $existing->quantity + $item->quantity]);
            } else {
                $item->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }

    public function count(): int
    {
        return (int) $this->getCart()->items()->sum('quantity');
    }
}
