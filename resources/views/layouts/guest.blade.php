<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('shop.name', 'MS Furniture') }} — {{ config('shop.tagline') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

        <!-- Scripts -->
   <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body class="text-[#17351d] antialiased" style="font-family:'DM Sans',sans-serif">
        <div class="min-h-screen grid lg:grid-cols-[.9fr_1.1fr] bg-[#fbfcf8]">
            <div class="hidden lg:block relative overflow-hidden">
                <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=1400&q=88" alt="Contemporary MS Furniture interior" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/5 to-transparent"></div>
                <div class="absolute left-12 right-12 bottom-12 text-white">
                    <p class="text-[11px] uppercase tracking-[.25em] text-[#b7ef82] mb-4">MS Furniture</p>
                    <p class="text-5xl leading-tight" style="font-family:'Playfair Display',serif">Designed for the way<br>you live.</p>
                </div>
            </div>
            <main class="min-h-screen flex flex-col">
                <div class="p-6 sm:p-9 flex justify-between items-center">
                    <a href="{{ route('home') }}" wire:navigate aria-label="MS Furniture home"><img src="{{ asset('images/ms-furniture-logo.svg') }}" alt="MS Furniture" class="h-16 w-auto"></a>
                    <a href="{{ route('home') }}" class="text-[10px] uppercase tracking-[.18em] border-b border-[#17351d]/30 pb-1">Back to store</a>
                </div>
                <div class="flex-1 flex items-center justify-center px-6 pb-20">
                    <div class="w-full max-w-[460px] bg-white border border-[#17351d]/10 p-7 sm:p-10 shadow-[0_24px_70px_rgba(23,53,29,.09)]">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
