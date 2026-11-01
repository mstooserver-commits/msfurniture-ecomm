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
<section class="relative min-h-[560px] md:min-h-[680px] h-[calc(100svh-106px)] md:h-[calc(100svh-120px)] max-h-[900px] overflow-hidden bg-stone-100"
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
            <div class="absolute inset-0 max-w-[1440px] mx-auto px-5 sm:px-7 lg:px-12 flex items-end pb-14 sm:pb-20 lg:pb-28">
                <div class="max-w-3xl min-w-0 text-white"><p class="eyebrow text-[#a9e56f] mb-4 sm:mb-5">MS Furniture · Designed for living</p><h1 class="font-display text-[clamp(2.75rem,8vw,5.5rem)] break-words leading-[.94] mb-5 sm:mb-7">{{ $slide['title'] }}</h1>@if($slide['subtitle'])<p class="max-w-xl text-sm sm:text-base text-white/80 leading-6 sm:leading-7 mb-6 sm:mb-8">{{ $slide['subtitle'] }}</p>@endif<a href="{{ $slide['link'] }}" class="inline-flex bg-[#9dde63] text-[#14331b] px-5 sm:px-7 py-3.5 sm:py-4 eyebrow hover:bg-white transition">{{ $slide['button'] }}</a></div>
            </div>
        </div>
    @empty
        <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=2000&q=90" alt="MS Furniture living room collection" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/20 to-transparent"></div>
        <div class="relative h-full max-w-[1440px] mx-auto px-5 sm:px-7 lg:px-12 flex items-end pb-14 sm:pb-20 lg:pb-28"><div class="max-w-3xl min-w-0 text-white"><p class="eyebrow text-[#a9e56f] mb-4 sm:mb-5">MS Furniture · Thoughtfully crafted</p><h1 class="font-display text-[clamp(2.75rem,8vw,5.5rem)] leading-[.94] mb-5 sm:mb-7">Spaces Worth<br><em>Living In.</em></h1><p class="max-w-xl text-sm sm:text-base text-white/80 leading-6 sm:leading-7 mb-6 sm:mb-8">Timeless furniture, thoughtfully crafted for the way you live.</p><a href="{{ route('shop') }}" class="inline-flex bg-[#9dde63] text-[#14331b] px-5 sm:px-7 py-3.5 sm:py-4 eyebrow hover:bg-white transition">Explore collection</a></div></div>
    @endforelse
    @if($heroSlides->count() > 1)<div class="absolute z-10 bottom-7 right-7 flex gap-2">@foreach($heroSlides as $index => $slide)<button @click="active={{ $index }}" class="h-1 transition-all" :class="active === {{ $index }} ? 'w-10 bg-[#9dde63]' : 'w-5 bg-white/50'" aria-label="Show slide {{ $index + 1 }}"></button>@endforeach</div>@endif
</section>

<section class="border-b border-[#252620]/10"><div class="max-w-[1440px] mx-auto grid grid-cols-2 lg:grid-cols-4 divide-x divide-[#252620]/10">
@foreach([['truck','Delivery across India'],['leaf','Thoughtfully crafted'],['shield','5-year warranty'],['chat','Design consultation']] as $benefit)<div class="py-6 px-4 text-center"><p class="eyebrow text-[#75806a]">{{ $benefit[1] }}</p></div>@endforeach
</div></section>

<section id="rooms" class="max-w-[1440px] mx-auto px-5 lg:px-10 py-20 lg:py-28">
    <div class="text-center max-w-2xl mx-auto mb-12"><p class="eyebrow text-[#75806a] mb-4">Spaces to settle into</p><h2 class="font-display text-4xl lg:text-6xl mb-5">Shop by room</h2><p class="text-stone-500 leading-7">Thoughtful foundations and finishing touches for every corner of your home.</p></div>
    @if($categories->isNotEmpty())
        <div class="grid sm:grid-cols-2 lg:grid-cols-12 gap-4 lg:gap-6">
            @foreach($categories as $category)
                @php
                    $categoryImage = $category->image
                        ? (str_starts_with($category->image, 'http') ? $category->image : asset('storage/'.$category->image))
                        : asset('images/product-placeholder.svg');
                @endphp
                <a href="{{ route('shop', ['category' => $category->slug]) }}"
                   class="group relative overflow-hidden min-h-[320px] {{ $loop->first ? 'lg:col-span-7 lg:row-span-2 lg:min-h-[680px]' : 'lg:col-span-5' }}">
                    <img src="{{ $categoryImage }}" class="w-full h-full absolute inset-0 object-cover group-hover:scale-105 transition duration-700" alt="{{ $category->name }} furniture">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-6 lg:p-8 flex items-end justify-between gap-4 text-white">
                        <span class="font-display text-3xl lg:text-4xl">{{ $category->name }}</span>
                        <span class="w-10 h-10 rounded-full border border-white/50 grid place-items-center group-hover:bg-white group-hover:text-[#17351d] transition">→</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>

@if($collections->isNotEmpty())
<section id="collections" class="bg-[#0B2818] text-white py-20 lg:py-28 overflow-hidden scroll-mt-32">
    <div class="max-w-[1440px] mx-auto px-5 lg:px-10"><div class="max-w-2xl mb-12"><p class="eyebrow text-[#9DDC68] mb-4">Curated collections</p><h2 class="font-display text-4xl lg:text-6xl">The Art of Living Beautifully</h2></div>
    <div class="flex gap-5 overflow-x-auto snap-x hide-scrollbar pb-3">@foreach($collections as $collection)<a href="{{ route('shop',['search'=>$collection->name]) }}" class="group min-w-[82vw] sm:min-w-[48%] lg:min-w-[31%] snap-start"><div class="aspect-[4/5] overflow-hidden bg-white/5 mb-5"><img src="{{ $collection->image ? (str_starts_with($collection->image,'http') ? $collection->image : asset('storage/'.$collection->image)) : asset('images/product-placeholder.svg') }}" alt="{{ $collection->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy"></div><h3 class="font-display text-3xl mb-2">{{ $collection->name }}</h3><p class="text-sm text-white/60 line-clamp-2 mb-4">{{ $collection->description }}</p><span class="eyebrow text-[#9DDC68]">Explore collection →</span></a>@endforeach</div></div>
</section>
@endif

@php $featured=$bestSellers->isNotEmpty()?$bestSellers:($trending->isNotEmpty()?$trending:$newArrivals); @endphp
@if($featured->isNotEmpty())<section class="bg-[#eee9df] py-20 lg:py-28"><div class="max-w-[1440px] mx-auto px-5 lg:px-10"><div class="flex items-end justify-between mb-10"><div><p class="eyebrow text-[#75806a] mb-3">Loved for a reason</p><h2 class="font-display text-4xl lg:text-6xl">The most wanted</h2></div><a href="{{ route('shop',['sort'=>'popular']) }}" class="hidden sm:flex items-center gap-2 eyebrow border-b border-[#252620] pb-1">View all <span>→</span></a></div><div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-10 lg:gap-6">@foreach($featured->take(8) as $product)<x-product-card :product="$product" />@endforeach</div></div></section>@endif

<section class="w-full max-w-[1440px] mx-auto px-4 sm:px-5 lg:px-10 py-14 sm:py-20 lg:py-28 overflow-hidden"><div class="grid grid-cols-1 lg:grid-cols-2 items-stretch bg-[#173B25] text-white overflow-hidden"><div class="w-full min-w-0 h-[300px] sm:h-[420px] lg:h-auto lg:min-h-[520px]"><img src="{{ $categories->first()?->image ? (str_starts_with($categories->first()->image,'http') ? $categories->first()->image : asset('storage/'.$categories->first()->image)) : asset('images/product-placeholder.svg') }}" alt="MS Furniture craftsmanship" class="block w-full h-full object-cover" loading="lazy"></div><div class="w-full min-w-0 p-6 py-10 sm:p-12 lg:p-16 xl:p-20"><p class="eyebrow text-[#9DDC68] mb-4 sm:mb-5">Made with intention</p><h2 class="font-display text-[2.25rem] sm:text-5xl lg:text-6xl leading-[1.02] mb-5 sm:mb-6 break-words">Designed for Living.<br class="hidden sm:block"> Made to Last.</h2><p class="text-sm sm:text-base text-white/75 leading-6 sm:leading-7 mb-7 sm:mb-8 max-w-md">Thoughtful proportions, enduring materials and skilled workmanship come together in furniture created for everyday comfort and years of living.</p><a href="{{ route('about') }}" class="inline-flex max-w-full items-center gap-3 eyebrow border-b border-white pb-2">Discover our story <span>→</span></a></div></div></section>

@if($newArrivals->isNotEmpty())<section class="max-w-[1440px] mx-auto px-5 lg:px-10 pb-24"><div class="text-center mb-10"><p class="eyebrow text-[#75806a] mb-3">Freshly arrived</p><h2 class="font-display text-4xl lg:text-6xl">New to the collection</h2></div><div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-10 lg:gap-6">@foreach($newArrivals->take(4) as $product)<x-product-card :product="$product" />@endforeach</div></section>@endif

@if($featured->isNotEmpty())
@php $lookProduct = $featured->first(); $lookImage = $lookProduct->images->first()?->path; @endphp
<section class="max-w-[1440px] mx-auto px-5 lg:px-10 pb-20 lg:pb-28" x-data="{open:false}"><div class="text-center mb-10"><p class="eyebrow text-[#777D73] mb-3">Room inspiration</p><h2 class="font-display text-4xl lg:text-6xl">Shop the Look</h2></div><div class="relative aspect-[4/5] md:aspect-[16/8] overflow-hidden bg-[#F2EFE8]"><img src="{{ $lookImage ? (str_starts_with($lookImage,'http') ? $lookImage : asset('storage/'.$lookImage)) : asset('images/product-placeholder.svg') }}" alt="A considered room by MS Furniture" class="w-full h-full object-cover" loading="lazy"><button @click="open=!open" class="absolute left-[54%] top-[54%] w-11 h-11 rounded-full bg-white text-[#0B2818] shadow-lg grid place-items-center text-xl" aria-label="View {{ $lookProduct->name }}" :aria-expanded="open">+</button><div x-show="open" x-cloak x-transition @click.outside="open=false" class="absolute left-[8%] md:left-[56%] top-[63%] w-[84%] md:w-72 bg-white p-4 shadow-xl flex gap-4"><div class="w-20 aspect-square bg-[#F2EFE8] overflow-hidden"><img src="{{ $lookImage ? (str_starts_with($lookImage,'http') ? $lookImage : asset('storage/'.$lookImage)) : asset('images/product-placeholder.svg') }}" alt="" class="w-full h-full object-cover"></div><div><p class="font-display text-xl leading-tight">{{ $lookProduct->name }}</p><p class="text-sm mt-1 mb-3">₹{{ number_format($lookProduct->effectivePrice(),0) }}</p><a href="{{ route('products.show',$lookProduct) }}" class="eyebrow border-b border-[#222822]">View product</a></div></div></div></section>
@endif

<section class="bg-[#F2EFE8] py-20 lg:py-28"><div class="max-w-[1440px] mx-auto px-5 lg:px-10"><div class="text-center max-w-2xl mx-auto mb-12"><p class="eyebrow text-[#777D73] mb-4">Our approach</p><h2 class="font-display text-4xl lg:text-6xl">Craftsmanship in Every Detail</h2></div><div class="grid md:grid-cols-3 border-y border-[#222822]/15 divide-y md:divide-y-0 md:divide-x divide-[#222822]/15">@foreach([['01','Responsibly Selected Materials','Materials chosen for their character, performance and enduring beauty.'],['02','Built for Everyday Living','Comfort and durability considered in every proportion and finish.'],['03','Designed with Purpose','Quietly distinctive pieces made to work beautifully in real homes.']] as $item)<article class="py-10 md:px-10 first:pl-0 last:pr-0"><span class="eyebrow text-[#9aab8c]">{{ $item[0] }}</span><h3 class="font-display text-3xl mt-8 mb-4">{{ $item[1] }}</h3><p class="text-sm leading-7 text-[#777D73]">{{ $item[2] }}</p></article>@endforeach</div></div></section>

@if($trending->isNotEmpty())<section class="max-w-[1440px] mx-auto px-5 lg:px-10 py-20 lg:py-28"><div class="flex items-end justify-between mb-10"><div><p class="eyebrow text-[#777D73] mb-3">Current favourites</p><h2 class="font-display text-4xl lg:text-6xl">Trending Now</h2></div><a href="{{ route('shop',['sort'=>'popular']) }}" class="eyebrow border-b border-[#222822] pb-1">View all →</a></div><div class="flex gap-5 overflow-x-auto snap-x hide-scrollbar">@foreach($trending as $product)<div class="min-w-[68vw] sm:min-w-[38%] lg:min-w-[23%] snap-start"><x-product-card :product="$product" /></div>@endforeach</div></section>@endif

<section class="border-y border-[#E5E8E0] py-16"><div class="max-w-[1440px] mx-auto px-5 lg:px-10 grid grid-cols-2 lg:grid-cols-5 gap-y-10">@foreach([['◇','Premium Quality'],['✦','Thoughtful Design'],['→','Reliable Delivery'],['○','Long-Lasting Comfort'],['+','Customer Support']] as $value)<div class="text-center lg:border-r last:border-0 border-[#E5E8E0] px-3"><span class="font-display text-3xl text-[#668657]">{{ $value[0] }}</span><p class="eyebrow mt-4">{{ $value[1] }}</p></div>@endforeach</div></section>

@if($testimonials->isNotEmpty())
<section class="bg-[#17351d] text-white py-20 lg:py-28 overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-5 lg:px-10">
        <div class="grid lg:grid-cols-[.7fr_1.3fr] gap-12 lg:gap-24 items-start">
            <div><p class="eyebrow text-[#9dde63] mb-4">At home with you</p><h2 class="font-display text-4xl lg:text-6xl leading-tight">Pieces that become part of the story.</h2></div>
            <div class="grid sm:grid-cols-2 gap-5">
                @foreach($testimonials->take(4) as $testimonial)
                <figure class="border border-white/15 p-7 lg:p-9 bg-white/[.03]">
                    <div class="text-[#9dde63] tracking-[.25em] text-xs mb-6">{{ str_repeat('★', $testimonial->rating) }}</div>
                    <blockquote class="font-display text-2xl leading-snug mb-8">“{{ $testimonial->content }}”</blockquote>
                    <figcaption class="text-xs uppercase tracking-[.14em] text-white/55"><span class="text-white">{{ $testimonial->name }}</span>@if($testimonial->role) · {{ $testimonial->role }}@endif</figcaption>
                </figure>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@if($instagram->isNotEmpty())
<section class="max-w-[1440px] mx-auto px-5 lg:px-10 py-20 lg:py-28">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5 mb-10"><div><p class="eyebrow text-[#75806a] mb-3">In real homes</p><h2 class="font-display text-4xl lg:text-6xl">The MS community</h2></div><p class="text-sm text-stone-500">Tag <strong class="text-[#17351d]">@msfurniture</strong> to share your space.</p></div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">@foreach($instagram->take(8) as $post)<a href="{{ $post->link ?: '#' }}" class="group aspect-square overflow-hidden bg-stone-100"><img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/'.$post->image) }}" alt="MS Furniture in a customer home" class="w-full h-full object-cover group-hover:scale-105 transition duration-700"></a>@endforeach</div>
</section>
@endif

@if($journal->isNotEmpty())
<section class="max-w-[1440px] mx-auto px-5 lg:px-10 pb-20 lg:pb-28"><div class="flex items-end justify-between mb-10"><div><p class="eyebrow text-[#777D73] mb-3">Design inspiration</p><h2 class="font-display text-4xl lg:text-6xl">From the Journal</h2></div><a href="{{ route('blog.index') }}" class="eyebrow border-b border-[#222822] pb-1">Explore all →</a></div><div class="grid md:grid-cols-3 gap-6">@foreach($journal as $article)<article class="group"><a href="{{ route('blog.show',$article) }}" class="block aspect-[4/3] overflow-hidden bg-[#F2EFE8] mb-5"><img src="{{ $article->featured_image ? (str_starts_with($article->featured_image,'http') ? $article->featured_image : asset('storage/'.$article->featured_image)) : asset('images/product-placeholder.svg') }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy"></a><p class="eyebrow text-[#777D73] mb-3">{{ $article->blogCategory?->name ?? 'Journal' }} · {{ $article->published_at->format('d M Y') }}</p><h3 class="font-display text-3xl leading-tight mb-4"><a href="{{ route('blog.show',$article) }}">{{ $article->title }}</a></h3><a href="{{ route('blog.show',$article) }}" class="eyebrow border-b border-[#222822]">Read article</a></article>@endforeach</div></section>
@endif

<section class="bg-[#0B2818] text-white py-20 lg:py-28 px-5 text-center"><p class="eyebrow text-[#9DDC68] mb-4">The MS Furniture edit</p><h2 class="font-display text-4xl lg:text-6xl mb-5">A More Beautiful Home Starts Here.</h2><p class="max-w-xl mx-auto text-white/60 mb-8 leading-7">Design inspiration, new collections and considered updates, delivered occasionally.</p><div class="max-w-lg mx-auto">@livewire('store.newsletter-signup')</div></section>
</div>
