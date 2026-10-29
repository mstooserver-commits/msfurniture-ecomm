<?php

namespace App\Livewire\Store;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WishlistPage extends Component
{
    public function remove(int $id): void
    {
        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();
    }

    public function render()
    {
        $items = Wishlist::where('user_id', Auth::id())
            ->with(['product.images', 'product.brand'])
            ->latest()
            ->get();

        return view('livewire.store.wishlist-page', compact('items'))
            ->layout('layouts.store');
    }
}
