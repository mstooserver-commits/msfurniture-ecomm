<div>
@php
    $heroSlides = $banners->map(fn ($banner) => [
        'title' => $banner->title,
        'subtitle' => $banner->subtitle,
        'image' => $banner->image ? (str_starts_with($banner->image, 'http') ? $banner->image : asset('storage/'.$banner->image)) : 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=2000&q=90',
        'video' => $banner->video ? asset('storage/'.$banner->video) : null,
        'link' => $banner->link ?: route('shop'),
        'button' => $banner->button_text ?: 'Shop now',
    ])->values();
@endphp
<section class="relative min-h-[650px] h-[calc(100vh-120px)] max-h-[900px] overflow-hidden bg-stone-100"
    x-data="{ active: 0 }"
    @if($heroSlides->count() > 1) x-init="setInterval(() => active = (active + 1) % {{ $heroSlides->count() }}, 6000)" @endif>
    @forelse($heroSlides as $index => $slide)
        <div x-show="active === {{ $index }}" x-transition.opacity.duration.700ms class="absolute inset-0">
            @if($slide['video'])
                <video class="w-full h-full object-cover" autoplay muted loop playsinline poster="{{ $slide['image'] }}"><source src="{{ $slide['video'] }}"></video>
            @elseif($slide['image'])
                <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/20 to-transparent"></div>
            <div class="absolute inset-0 max-w-[1440px] mx-auto px-6 lg:px-12 flex items-end pb-20 lg:pb-28">
                <div class="max-w-3xl text-white"><p class="eyebrow text-[#a9e56f] mb-5">MS Furniture · Designed for living</p><h1 class="font-display text-5xl sm:text-7xl lg:text-[88px] leading-[.96] mb-7">{{ $slide['title'] }}</h1>@if($slide['subtitle'])<p class="max-w-xl text-sm sm:text-base text-white/80 leading-7 mb-8">{{ $slide['subtitle'] }}</p>@endif<a href="{{ $slide['link'] }}" class="inline-flex bg-[#9dde63] text-[#14331b] px-7 py-4 eyebrow hover:bg-white transition">{{ $slide['button'] }}</a></div>
            </div>
        </div>
    @empty
        <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=2000&q=90" alt="MS Furniture living room" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/20 to-transparent"></div>
        <div class="relative h-full max-w-[1440px] mx-auto px-6 lg:px-12 flex items-end pb-20 lg:pb-28"><div class="max-w-3xl text-white"><p class="eyebrow text-[#a9e56f] mb-5">MS Furniture · Australian living</p><h1 class="font-display text-5xl sm:text-7xl lg:text-[88px] leading-[.96] mb-7">Furniture made<br><em>to feel at home.</em></h1><p class="max-w-xl text-white/70 leading-7 mb-8">Thoughtful pieces for comfortable, contemporary spaces.</p><a href="{{ route('shop') }}" class="inline-flex bg-[#9dde63] text-[#14331b] px-7 py-4 eyebrow hover:bg-white transition">Explore the collection</a></div></div>
    @endforelse
    @if($heroSlides->count() > 1)<div class="absolute z-10 bottom-7 right-7 flex gap-2">@foreach($heroSlides as $index => $slide)<button @click="active={{ $index }}" class="h-1 transition-all" :class="active === {{ $index }} ? 'w-10 bg-[#9dde63]' : 'w-5 bg-white/50'" aria-label="Show slide {{ $index + 1 }}"></button>@endforeach</div>@endif
</section>

<section class="border-b border-[#252620]/10"><div class="max-w-[1440px] mx-auto grid grid-cols-2 lg:grid-cols-4 divide-x divide-[#252620]/10">
@foreach([['truck','Australia-wide delivery'],['leaf','Responsibly sourced'],['shield','5-year warranty'],['chat','Design advice, on us']] as $benefit)<div class="py-6 px-4 text-center"><p class="eyebrow text-[#75806a]">{{ $benefit[1] }}</p></div>@endforeach
</div></section>

<section id="rooms" class="max-w-[1440px] mx-auto px-5 lg:px-10 py-20 lg:py-28">
    <div class="text-center max-w-2xl mx-auto mb-12"><p class="eyebrow text-[#75806a] mb-4">Spaces to settle into</p><h2 class="font-display text-4xl lg:text-6xl mb-5">Shop by room</h2><p class="text-stone-500 leading-7">Thoughtful foundations and finishing touches for every corner of your home.</p></div>
    <div class="grid md:grid-cols-12 gap-4 lg:gap-6">
        <a href="{{ route('shop',['category'=>'living-room']) }}" class="group md:col-span-7 relative aspect-[4/3] overflow-hidden"><img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1400&q=85" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" alt="Living room"><div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div><span class="absolute left-6 bottom-6 text-white font-display text-3xl lg:text-4xl">Living Room</span></a>
        <div class="md:col-span-5 grid grid-cols-2 md:grid-cols-1 gap-4 lg:gap-6"><a href="{{ route('shop',['category'=>'dining']) }}" class="group relative overflow-hidden"><img src="https://images.unsplash.com/photo-1617806118233-18e1de247200?w=1000&q=85" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" alt="Dining room"><div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div><span class="absolute left-5 bottom-5 text-white font-display text-2xl lg:text-3xl">Dining</span></a><a href="{{ route('shop',['category'=>'bedroom']) }}" class="group relative overflow-hidden"><img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1000&q=85" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" alt="Bedroom"><div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div><span class="absolute left-5 bottom-5 text-white font-display text-2xl lg:text-3xl">Bedroom</span></a></div>
    </div>
</section>

@php $featured=$bestSellers->isNotEmpty()?$bestSellers:($trending->isNotEmpty()?$trending:$newArrivals); @endphp
@if($featured->isNotEmpty())<section class="bg-[#eee9df] py-20 lg:py-28"><div class="max-w-[1440px] mx-auto px-5 lg:px-10"><div class="flex items-end justify-between mb-10"><div><p class="eyebrow text-[#75806a] mb-3">Loved for a reason</p><h2 class="font-display text-4xl lg:text-6xl">The most wanted</h2></div><a href="{{ route('shop',['sort'=>'popular']) }}" class="hidden sm:flex items-center gap-2 eyebrow border-b border-[#252620] pb-1">View all <span>→</span></a></div><div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-10 lg:gap-6">@foreach($featured->take(8) as $product)<x-product-card :product="$product" />@endforeach</div></div></section>@endif

<section class="max-w-[1440px] mx-auto px-5 lg:px-10 py-20 lg:py-28"><div class="grid lg:grid-cols-2 items-center bg-[#75806a] text-white"><div class="aspect-[5/4] lg:aspect-auto lg:h-full min-h-[520px]"><img src="https://images.unsplash.com/photo-1615874694520-474822394e73?w=1400&q=85" alt="Crafted timber furniture" class="w-full h-full object-cover"></div><div class="p-10 sm:p-16 lg:p-20"><p class="eyebrow text-white/65 mb-5">Made with intention</p><h2 class="font-display text-4xl lg:text-6xl leading-tight mb-6">Furniture with a quieter footprint.</h2><p class="text-white/75 leading-7 mb-8 max-w-md">We work with enduring materials and skilled makers to create pieces worth keeping. Less trend, more story—with a five-year structural warranty as standard.</p><a href="{{ route('about') }}" class="inline-flex items-center gap-3 eyebrow border-b border-white pb-2">Discover our approach <span>→</span></a></div></div></section>

@if($newArrivals->isNotEmpty())<section class="max-w-[1440px] mx-auto px-5 lg:px-10 pb-24"><div class="text-center mb-10"><p class="eyebrow text-[#75806a] mb-3">Freshly arrived</p><h2 class="font-display text-4xl lg:text-6xl">New to the collection</h2></div><div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-10 lg:gap-6">@foreach($newArrivals->take(4) as $product)<x-product-card :product="$product" />@endforeach</div></section>@endif

<section class="bg-[#d7cbb8] py-20 px-5 text-center"><p class="eyebrow text-[#75806a] mb-4">The Arden edit</p><h2 class="font-display text-4xl lg:text-6xl mb-5">Come home inspired.</h2><p class="max-w-xl mx-auto text-stone-600 mb-8 leading-7">Join our community for studio notes, thoughtful interiors and first access to new collections.</p><div class="max-w-md mx-auto">@livewire('store.newsletter-signup')</div></section>
</div>
