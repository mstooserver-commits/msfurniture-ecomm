<?php

namespace App\Livewire\Store;

use App\Models\Banner;
use App\Models\Category;
use App\Models\InstagramPost;
use App\Models\Product;
use App\Models\Testimonial;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        return view('livewire.store.home-page', [
            'banners' => Banner::where('is_active', true)->where('placement', 'hero')->orderBy('sort_order')->get(),
            'trending' => Product::active()->where('is_trending', true)->with(['images', 'brand'])->limit(8)->get(),
            'newArrivals' => Product::active()->where('is_new_arrival', true)->with(['images', 'brand'])->limit(8)->get(),
            'bestSellers' => Product::active()->where('is_best_seller', true)->with(['images', 'brand'])->limit(8)->get(),
            'flashSale' => Product::active()->where('is_flash_sale', true)->with(['images', 'brand'])->limit(4)->get(),
            'categories' => Category::active()->where('is_featured', true)->orderBy('sort_order')->limit(6)->get(),
            'testimonials' => Testimonial::where('is_active', true)->orderBy('sort_order')->limit(6)->get(),
            'instagram' => InstagramPost::where('is_active', true)->orderBy('sort_order')->limit(8)->get(),
        ])->layout('layouts.store');
    }
}
