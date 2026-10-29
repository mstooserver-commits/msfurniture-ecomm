@extends('layouts.store')

@section('content')
<section class="relative overflow-hidden bg-[#17351d] text-white">
    <div class="absolute inset-0 opacity-25 bg-[radial-gradient(circle_at_80%_20%,#9dde63_0,transparent_34%)]"></div>
    <div class="relative max-w-[1440px] mx-auto px-6 lg:px-10 py-24 lg:py-36 grid lg:grid-cols-[1.2fr_.8fr] gap-16 items-end">
        <div>
            <p class="eyebrow text-[#9dde63] mb-6">Our story</p>
            <h1 class="font-display text-5xl sm:text-7xl lg:text-[86px] leading-[.98]">Furniture made<br><em>for living.</em></h1>
        </div>
        <p class="text-white/70 leading-8 max-w-lg">MS Furniture brings together comfortable forms, enduring materials and practical design for Australian homes. We believe the best rooms feel personal, relaxed and easy to live in.</p>
    </div>
</section>

<section class="max-w-[1440px] mx-auto px-6 lg:px-10 py-20 lg:py-28">
    <div class="grid lg:grid-cols-2 gap-14 lg:gap-24 items-center">
        <div class="aspect-[4/3] bg-[#e7eee0] overflow-hidden">
            <img src="https://images.unsplash.com/photo-1615874694520-474822394e73?w=1400&q=85" alt="Crafted furniture in a calm interior" class="w-full h-full object-cover">
        </div>
        <div class="max-w-xl">
            <p class="eyebrow text-[#5f8c52] mb-4">The MS approach</p>
            <h2 class="font-display text-4xl lg:text-6xl leading-tight mb-7">Good design should feel effortless.</h2>
            <div class="space-y-5 text-stone-600 leading-7">
                <p>We choose pieces that balance beauty with everyday usefulness—generous seating, thoughtful storage and finishes that settle naturally into your home.</p>
                <p>Our collection is shaped by lasting quality rather than passing trends, so every piece can become part of your home’s story.</p>
            </div>
            <a href="{{ route('shop') }}" class="btn-dark mt-9">Explore our collection <span>→</span></a>
        </div>
    </div>
</section>

<section class="bg-[#eef3e8] py-20 lg:py-24">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-10">
        <div class="grid md:grid-cols-3 gap-px bg-[#17351d]/10">
            @foreach([
                ['01', 'Comfort first', 'Pieces selected to feel as good as they look, every single day.'],
                ['02', 'Made to last', 'Honest materials and enduring construction for years of living.'],
                ['03', 'Here to help', 'Friendly advice to help you find the right piece for your space.'],
            ] as [$number, $title, $copy])
                <article class="bg-[#eef3e8] p-8 lg:p-12">
                    <span class="eyebrow text-[#5f8c52]">{{ $number }}</span>
                    <h3 class="font-display text-3xl mt-10 mb-4">{{ $title }}</h3>
                    <p class="text-sm text-stone-600 leading-6">{{ $copy }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
