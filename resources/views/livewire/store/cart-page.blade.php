<div class="max-w-7xl mx-auto px-4 lg:px-8 py-8">
    <h1 class="font-display text-4xl mb-10">Your Cart</h1>

    @if($cart->items->isEmpty())
        <div class="text-center py-20">
            <p class="text-zinc-500 mb-6">Your cart is empty.</p>
            <a href="{{ route('shop') }}" class="inline-block border border-zinc-900 px-8 py-3 text-xs uppercase tracking-widest hover:bg-zinc-900 hover:text-white transition">Continue Shopping</a>
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-6">
                @foreach($cart->items as $item)
                    <div class="flex gap-4 border-b border-zinc-200 pb-6" wire:key="cart-{{ $item->id }}">
                        @php $img = $item->product->images->first(); @endphp
                        <img src="{{ $img ? asset('storage/'.$img->path) : 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=200&q=80' }}"
                             class="w-24 h-32 object-cover bg-zinc-100" alt="">
                        <div class="flex-1">
                            <a href="{{ route('products.show', $item->product) }}" class="font-medium hover:text-zinc-900">{{ $item->product->name }}</a>
                            @if($item->variant)<p class="text-xs text-zinc-500 mt-1">{{ $item->variant->size }} {{ $item->variant->color }}</p>@endif
                            <p class="text-sm mt-2">${{ number_format($item->unit_price, 2) }}</p>
                            <div class="flex items-center gap-4 mt-3">
                                <div class="flex border border-zinc-300 text-sm">
                                    <button wire:click="updateQty({{ $item->id }}, {{ $item->quantity - 1 }})" class="px-3 py-1">−</button>
                                    <span class="px-3 py-1">{{ $item->quantity }}</span>
                                    <button wire:click="updateQty({{ $item->id }}, {{ $item->quantity + 1 }})" class="px-3 py-1">+</button>
                                </div>
                                <button wire:click="remove({{ $item->id }})" class="text-xs text-zinc-500 hover:text-red-400 uppercase tracking-widest">Remove</button>
                            </div>
                        </div>
                        <p class="font-medium">${{ number_format($item->lineTotal(), 2) }}</p>
                    </div>
                @endforeach
            </div>
            <div class="glass p-8 h-fit">
                <h2 class="text-xs uppercase tracking-widest text-zinc-500 mb-6">Order Summary</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-zinc-500">Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div>
                    @if($discount > 0)<div class="flex justify-between text-emerald-700"><span>Discount</span><span>-${{ number_format($discount, 2) }}</span></div>@endif
                    <div class="flex justify-between"><span class="text-zinc-500">Tax</span><span>${{ number_format($tax, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-zinc-500">Shipping</span><span>{{ $shipping > 0 ? '$'.number_format($shipping, 2) : 'FREE' }}</span></div>
                    <div class="flex justify-between text-lg font-semibold pt-3 border-t border-zinc-200">
                        <span>Total</span>
                        <span>${{ number_format($subtotal - $discount + $tax + $shipping, 2) }}</span>
                    </div>
                </div>
                <form wire:submit="applyCoupon" class="mt-6 flex gap-2">
                    <input wire:model="couponCode" placeholder="Coupon code" class="flex-1 bg-white border border-zinc-300 px-3 py-2 text-sm text-zinc-900">
                    <button type="submit" class="px-4 py-2 border border-zinc-300 text-xs uppercase tracking-widest hover:border-zinc-900">Apply</button>
                </form>
                <a href="{{ route('checkout') }}" class="block w-full text-center bg-zinc-900 text-white py-4 mt-6 text-xs uppercase tracking-widest hover:bg-zinc-800 transition">Checkout</a>
            </div>
        </div>
    @endif
</div>
