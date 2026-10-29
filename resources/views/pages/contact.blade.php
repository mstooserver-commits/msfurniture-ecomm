@extends('layouts.store')

@section('content')
<section class="bg-[#eef3e8]">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-10 py-20 lg:py-28 grid lg:grid-cols-2 gap-14 lg:gap-24">
        <div>
            <p class="eyebrow text-[#5f8c52] mb-5">Contact MS Furniture</p>
            <h1 class="font-display text-5xl lg:text-7xl leading-tight mb-7">Let’s find the right piece.</h1>
            <p class="text-stone-600 leading-7 max-w-lg">Need product advice, delivery information or help with an order? Send us a message and our team will be happy to help.</p>
        </div>
        <div class="bg-white p-7 sm:p-10 shadow-[0_20px_60px_rgba(23,53,29,.08)]">
            <form action="mailto:hello@msfurniture.com.au" method="post" enctype="text/plain" class="space-y-6">
                <div><label class="eyebrow block mb-2" for="name">Name</label><input id="name" name="name" required class="w-full border border-[#17351d]/20 px-4 py-3 bg-transparent focus:border-[#5f8c52] focus:ring-0"></div>
                <div><label class="eyebrow block mb-2" for="email">Email</label><input id="email" name="email" type="email" required class="w-full border border-[#17351d]/20 px-4 py-3 bg-transparent focus:border-[#5f8c52] focus:ring-0"></div>
                <div><label class="eyebrow block mb-2" for="message">How can we help?</label><textarea id="message" name="message" required rows="5" class="w-full border border-[#17351d]/20 px-4 py-3 bg-transparent focus:border-[#5f8c52] focus:ring-0"></textarea></div>
                <button class="btn-dark" type="submit">Send message <span>→</span></button>
            </form>
        </div>
    </div>
</section>
@endsection
