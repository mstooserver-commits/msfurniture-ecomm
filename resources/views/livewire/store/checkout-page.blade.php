<div class="max-w-7xl mx-auto px-4 lg:px-8 py-8">
    <h1 class="font-display text-4xl mb-10">Checkout</h1>

    @if($totals['cart']->items->isEmpty())
        <p class="text-zinc-500">Your cart is empty. <a href="{{ route('shop') }}" class="text-zinc-900 underline">Shop now</a></p>
    @else
        <form wire:submit="placeOrder" class="grid lg:grid-cols-2 gap-10">
            <div class="space-y-6">
                @guest
                    <div>
                        <label class="text-xs uppercase tracking-widest text-zinc-500">Email</label>
                        <input wire:model="guestEmail" type="email" required class="w-full mt-2 bg-white border border-zinc-300 text-zinc-900 px-4 py-3 text-sm">
                    </div>
                @endguest
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs uppercase tracking-widest text-zinc-500">First Name</label>
                        <input wire:model="shipping.first_name" required class="w-full mt-2 bg-white border border-zinc-300 text-zinc-900 px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-widest text-zinc-500">Last Name</label>
                        <input wire:model="shipping.last_name" required class="w-full mt-2 bg-white border border-zinc-300 text-zinc-900 px-4 py-3 text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-widest text-zinc-500">Address</label>
                    <input wire:model="shipping.address_line_1" required class="w-full mt-2 bg-white border border-zinc-300 text-zinc-900 px-4 py-3 text-sm">
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs uppercase tracking-widest text-zinc-500">City</label>
                        <input wire:model="shipping.city" required class="w-full mt-2 bg-white border border-zinc-300 text-zinc-900 px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-widest text-zinc-500">State</label>
                        <input wire:model="shipping.state" required class="w-full mt-2 bg-white border border-zinc-300 text-zinc-900 px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-widest text-zinc-500">Postcode</label>
                        <input wire:model="shipping.postal_code" required class="w-full mt-2 bg-white border border-zinc-300 text-zinc-900 px-4 py-3 text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-widest text-zinc-500 mb-3 block">Payment Method</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-4 border border-zinc-200 cursor-pointer">
                            <input type="radio" wire:model="paymentMethod" value="cod" class="accent-zinc-900">
                            <span class="text-sm">Cash on Delivery</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 border border-zinc-200 cursor-pointer">
                            <input type="radio" wire:model="paymentMethod" value="stripe" class="accent-zinc-900">
                            <span class="text-sm">Stripe (Card)</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 border border-zinc-200 cursor-pointer">
                            <input type="radio" wire:model="paymentMethod" value="razorpay" class="accent-zinc-900">
                            <span class="text-sm">Razorpay</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="glass p-8 h-fit">
                <h2 class="text-xs uppercase tracking-widest text-zinc-500 mb-6">Order Total</h2>
                <div class="space-y-3 text-sm mb-6">
                    <div class="flex justify-between"><span class="text-zinc-500">Subtotal</span><span>${{ number_format($totals['subtotal'], 2) }}</span></div>
                    @if($totals['discount'] > 0)<div class="flex justify-between text-emerald-700"><span>Discount</span><span>-${{ number_format($totals['discount'], 2) }}</span></div>@endif
                    <div class="flex justify-between"><span class="text-zinc-500">Tax</span><span>${{ number_format($totals['tax'], 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-zinc-500">Shipping</span><span>${{ number_format($totals['shipping'], 2) }}</span></div>
                    <div class="flex justify-between text-xl font-semibold pt-3 border-t border-zinc-200">
                        <span>Total</span><span>${{ number_format($totals['total'], 2) }}</span>
                    </div>
                </div>
                <button type="submit" class="w-full bg-zinc-900 text-white py-4 text-xs uppercase tracking-widest hover:bg-zinc-800 transition">Place Order</button>
            </div>
        </form>
    @endif
</div>
