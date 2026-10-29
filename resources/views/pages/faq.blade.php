@extends('layouts.store')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="font-display text-5xl mb-12 text-center">FAQ</h1>
    @foreach($faqs as $category => $items)
        <h2 class="text-xs uppercase tracking-widest text-zinc-500 mb-4 mt-8">{{ ucfirst($category) }}</h2>
        <div class="space-y-4">
            @foreach($items as $faq)
                <details class="glass p-6 group">
                    <summary class="font-medium cursor-pointer list-none flex justify-between items-center">
                        {{ $faq->question }}
                        <span class="text-zinc-500 group-open:rotate-45 transition">+</span>
                    </summary>
                    <p class="mt-4 text-sm text-zinc-400 leading-relaxed">{{ $faq->answer }}</p>
                </details>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
