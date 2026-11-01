@props(['product'])
@php
    $stored=$product->images->first()?->path;
    $image=$stored ? (str_starts_with($stored,'http') ? $stored : asset('storage/'.$stored)) : asset('images/product-placeholder.svg');
    $alternateStored=$product->images->skip(1)->first()?->path;
    $alternate=$alternateStored ? (str_starts_with($alternateStored,'http') ? $alternateStored : asset('storage/'.$alternateStored)) : null;
    $price=$product->effectivePrice();
@endphp
<article {{ $attributes->merge(['class'=>'group']) }}>
    <a href="{{ route('products.show',$product) }}" class="block relative aspect-[4/5] overflow-hidden bg-[#eee9df] mb-4">
        <img src="{{ $image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-700 ease-out {{ $alternate ? 'group-hover:opacity-0' : 'group-hover:scale-[1.035]' }}" loading="lazy">
        @if($alternate)<img src="{{ $alternate }}" alt="{{ $product->name }} alternate view" class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition duration-700 ease-out" loading="lazy">@endif
        @if($product->is_flash_sale)<span class="absolute top-3 left-3 bg-[#a85f3d] text-white eyebrow px-3 py-2">Sale</span>@elseif($product->is_new_arrival)<span class="absolute top-3 left-3 bg-[#f5f1e8] eyebrow px-3 py-2">New</span>@endif
        <span class="absolute bottom-3 left-3 right-3 bg-[#fbfaf6]/95 backdrop-blur-sm py-3 text-center eyebrow translate-y-16 group-hover:translate-y-0 transition duration-300">Quick view</span>
    </a>
    <div class="flex items-start justify-between gap-4"><div><p class="eyebrow text-[#75806a] mb-1.5">{{ $product->brand?->name ?? 'MS Furniture' }}</p><a href="{{ route('products.show',$product) }}" class="font-display text-xl leading-tight hover:text-[#75806a]">{{ $product->name }}</a></div><div class="text-right shrink-0"><span class="text-sm">₹{{ number_format($price,0) }}</span>@if($product->compare_at_price && $product->compare_at_price>$price)<span class="block text-xs text-stone-400 line-through">₹{{ number_format($product->compare_at_price,0) }}</span><span class="block text-[10px] text-[#58794c] mt-1">{{ round((($product->compare_at_price-$price)/$product->compare_at_price)*100) }}% off</span>@endif</div></div>
</article>
