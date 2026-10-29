<div class="max-w-7xl mx-auto px-4 lg:px-8 py-8">
    <h1 class="font-display text-4xl mb-10">Wishlist</h1>
    @if($items->isEmpty())
        <p class="text-zinc-500">Your wishlist is empty.</p>
    @else
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @foreach($items as $item)
                <div class="relative">
                    <x-product-card :product="$item->product" />
                    <button wire:click="remove({{ $item->id }})" class="absolute top-2 right-2 p-2 glass text-xs uppercase tracking-widest text-zinc-900 hover:bg-red-50 hover:text-red-600">Remove</button>
                </div>
            @endforeach
        </div>
    @endif
</div>
