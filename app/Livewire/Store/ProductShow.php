<?php

namespace App\Livewire\Store;

use App\Models\Product;
use App\Models\RecentlyViewedProduct;
use App\Models\Review;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductShow extends Component
{
    public Product $product;

    public ?int $variantId = null;

    public int $quantity = 1;

    public int $selectedImage = 0;

    public ?string $cartMessage = null;

    public function mount(Product $product): void
    {
        $this->product = $product->load(['images', 'videos', 'variants', 'brand', 'category', 'reviews.user']);
        $this->variantId = $this->product->variants
            ->first(fn ($variant) => $variant->is_active && $variant->stock > 0)?->id;
        $this->product->increment('views_count');
        $this->recordView();
    }

    protected function recordView(): void
    {
        RecentlyViewedProduct::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'session_id' => Auth::check() ? null : session()->getId(),
                'product_id' => $this->product->id,
            ],
            ['viewed_at' => now()]
        );
    }

    public function selectVariant(int $id): void
    {
        $variant = $this->product->variants->firstWhere('id', $id);

        if ($variant?->is_active && $variant->stock > 0) {
            $this->variantId = $id;
            $this->cartMessage = null;
        }
    }

    public function incrementQuantity(): void
    {
        $available = $this->selectedVariantStock();
        $this->quantity = min($this->quantity + 1, max(1, $available));
    }

    public function decrementQuantity(): void
    {
        $this->quantity = max(1, $this->quantity - 1);
    }

    public function addToCart(CartService $cart): void
    {
        if ($this->product->variants->isNotEmpty() && ! $this->variantId) {
            $this->addError('variantId', 'Please select an available finish.');
            return;
        }

        $variant = $this->variantId
            ? $this->product->variants()->find($this->variantId)
            : null;

        if (($variant && (! $variant->is_active || $variant->stock < $this->quantity)) || (! $variant && $this->product->stock < $this->quantity)) {
            $this->addError('quantity', 'The requested quantity is not available.');
            return;
        }

        $cart->add($this->product, $variant, $this->quantity);
        $this->dispatch('cart-updated');
        $this->cartMessage = "{$this->product->name} added to your cart.";
    }

    public function buyNow(CartService $cart): void
    {
        $this->addToCart($cart);

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $this->redirect(route('checkout'), navigate: true);
    }

    private function selectedVariantStock(): int
    {
        if (! $this->variantId) {
            return $this->product->stock;
        }

        return (int) ($this->product->variants->firstWhere('id', $this->variantId)?->stock ?? 0);
    }

    public function toggleWishlist(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'), navigate: true);

            return;
        }

        $exists = Wishlist::where('user_id', Auth::id())->where('product_id', $this->product->id)->exists();

        if ($exists) {
            Wishlist::where('user_id', Auth::id())->where('product_id', $this->product->id)->delete();
            session()->flash('success', 'Removed from wishlist.');
        } else {
            Wishlist::create(['user_id' => Auth::id(), 'product_id' => $this->product->id]);
            session()->flash('success', 'Added to wishlist.');
        }
    }

    public function render()
    {
        $related = Product::active()
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->with('images')
            ->limit(4)
            ->get();

        $recent = RecentlyViewedProduct::with('product.images')
            ->when(Auth::check(), fn ($q) => $q->where('user_id', Auth::id()))
            ->when(! Auth::check(), fn ($q) => $q->where('session_id', session()->getId()))
            ->where('product_id', '!=', $this->product->id)
            ->latest('viewed_at')
            ->limit(4)
            ->get()
            ->pluck('product')
            ->filter();

        return view('livewire.store.product-show', [
            'related' => $related,
            'recent' => $recent,
            'inWishlist' => Auth::check() && Wishlist::where('user_id', Auth::id())->where('product_id', $this->product->id)->exists(),
        ])->layout('layouts.store');
    }
}
