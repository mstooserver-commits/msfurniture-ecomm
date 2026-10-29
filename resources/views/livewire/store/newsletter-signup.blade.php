@if($subscribed)
    <p class="text-emerald-700 text-sm">You're on the list. Welcome to the collective.</p>
@else
    <form wire:submit="subscribe" class="flex gap-2">
        <input wire:model="email" type="email" placeholder="your@email.com" required
               class="flex-1 bg-white border border-zinc-300 px-4 py-2 text-sm text-zinc-900 focus:border-zinc-900 outline-none">
        <button type="submit" class="px-4 py-2 bg-zinc-900 text-white text-xs uppercase tracking-widest hover:bg-zinc-800 transition">Join</button>
    </form>
    @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
@endif
