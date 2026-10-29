@extends('layouts.store')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12 bg-white">
    <h1 class="font-display text-4xl mb-8 text-zinc-900">My Account</h1>
    <div class="grid md:grid-cols-4 gap-6">
        <nav class="space-y-2 text-sm">
            <a href="{{ route('account') }}" class="block text-zinc-900 font-medium">Dashboard</a>
            <a href="{{ route('wishlist') }}" class="block text-zinc-500 hover:text-zinc-900">Wishlist</a>
            <a href="{{ route('profile') }}" class="block text-zinc-500 hover:text-zinc-900">Profile</a>
        </nav>
        <div class="md:col-span-3 glass p-8">
            <p class="text-zinc-600 mb-6">Welcome back, {{ auth()->user()->name }}.</p>
            <div class="grid sm:grid-cols-3 gap-4 mb-8">
                <div class="border border-zinc-200 p-4 bg-white">
                    <p class="text-xs uppercase tracking-widest text-zinc-500">Wallet</p>
                    <p class="text-2xl font-semibold mt-1 text-zinc-900">${{ number_format(auth()->user()->wallet_balance, 2) }}</p>
                </div>
                <div class="border border-zinc-200 p-4 bg-white">
                    <p class="text-xs uppercase tracking-widest text-zinc-500">Reward Points</p>
                    <p class="text-2xl font-semibold mt-1 text-zinc-900">{{ auth()->user()->reward_points }}</p>
                </div>
                <div class="border border-zinc-200 p-4 bg-white">
                    <p class="text-xs uppercase tracking-widest text-zinc-500">Orders</p>
                    <p class="text-2xl font-semibold mt-1 text-zinc-900">{{ auth()->user()->orders()->count() }}</p>
                </div>
            </div>
            <h2 class="text-xs uppercase tracking-widest text-zinc-500 mb-4">Recent Orders</h2>
            @forelse(auth()->user()->orders()->latest()->limit(5)->get() as $order)
                <a href="{{ route('orders.show', $order) }}" class="flex justify-between py-3 border-b border-zinc-200 hover:text-zinc-600 text-zinc-900">
                    <span>{{ $order->order_number }}</span>
                    <span>${{ number_format($order->total, 2) }} · {{ $order->status->label() }}</span>
                </a>
            @empty
                <p class="text-zinc-500 text-sm">No orders yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
