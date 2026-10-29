@extends('layouts.store')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12 bg-white">
    <h1 class="font-display text-4xl mb-2 text-zinc-900">Order {{ $order->order_number }}</h1>
    <p class="text-zinc-500 text-sm mb-8">{{ $order->created_at->format('F j, Y g:i A') }} · {{ $order->status->label() }}</p>

    <div class="glass p-6 mb-8">
        <h2 class="text-xs uppercase tracking-widest text-zinc-500 mb-4">Timeline</h2>
        <div class="space-y-4">
            @foreach($order->statusHistory as $history)
                <div class="flex gap-4">
                    <div class="w-2 h-2 rounded-full bg-zinc-900 mt-2"></div>
                    <div>
                        <p class="font-medium text-sm text-zinc-900">{{ ucfirst($history->status) }}</p>
                        <p class="text-xs text-zinc-500">{{ $history->created_at->format('M d, Y H:i') }}</p>
                        @if($history->note)<p class="text-sm text-zinc-600">{{ $history->note }}</p>@endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="space-y-4 mb-8">
        @foreach($order->items as $item)
            <div class="flex justify-between border-b border-zinc-200 pb-4 text-zinc-900">
                <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                <span>${{ number_format($item->total, 2) }}</span>
            </div>
        @endforeach
    </div>

    <div class="flex justify-between text-lg font-semibold mb-6 text-zinc-900">
        <span>Total</span>
        <span>${{ number_format($order->total, 2) }}</span>
    </div>

    <a href="{{ route('orders.invoice', $order) }}" class="inline-block border border-zinc-900 px-6 py-3 text-xs uppercase tracking-widest hover:bg-zinc-900 hover:text-white transition">Download Invoice</a>
</div>
@endsection
