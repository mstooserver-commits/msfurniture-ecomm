@if($subscribed)
    <p class="text-[#9DDC68] text-sm" role="status">You’re on the list. Welcome to MS Furniture.</p>
@else
    <form wire:submit="subscribe" class="flex flex-col sm:flex-row gap-2">
        <label for="newsletter-email-{{ $this->getId() }}" class="sr-only">Email address</label><input id="newsletter-email-{{ $this->getId() }}" wire:model="email" type="email" placeholder="Email address" autocomplete="email" required
               class="flex-1 min-w-0 bg-white border border-white/30 px-4 py-3 text-sm text-[#222822] focus:border-[#9DDC68] outline-none">
        <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto px-5 py-3 bg-[#9DDC68] text-[#0B2818] text-xs uppercase tracking-widest hover:bg-white transition disabled:opacity-60"><span wire:loading.remove>Subscribe</span><span wire:loading>Joining…</span></button>
    </form>
    @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
@endif
