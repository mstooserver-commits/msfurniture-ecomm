<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\InstagramPost;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\Subcategory;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EcommerceSeeder extends Seeder
{
    public function run(): void
    {
        $brands = collect(['NOIR', 'Street Labs', 'Urban Drift', 'Collective Co'])->map(fn ($name) => Brand::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name, 'is_active' => true]
        ));

        $hoodies = Category::firstOrCreate(['slug' => 'hoodies'], [
            'name' => 'Hoodies', 'is_featured' => true, 'is_active' => true, 'sort_order' => 1,
            'image' => null,
        ]);
        $tees = Category::firstOrCreate(['slug' => 'tees'], [
            'name' => 'Tees', 'is_featured' => true, 'is_active' => true, 'sort_order' => 2,
        ]);
        $pants = Category::firstOrCreate(['slug' => 'pants'], [
            'name' => 'Pants', 'is_featured' => true, 'is_active' => true, 'sort_order' => 3,
        ]);

        Subcategory::firstOrCreate(['slug' => 'oversized-hoodies'], [
            'category_id' => $hoodies->id, 'name' => 'Oversized', 'is_active' => true,
        ]);

        Banner::firstOrCreate(['title' => 'DEFINE YOUR NOIR'], [
            'subtitle' => 'Premium Australian Streetwear',
            'placement' => 'hero',
            'is_active' => true,
            'sort_order' => 1,
            'link' => '/shop',
            'button_text' => 'Shop Collection',
        ]);

        Banner::firstOrCreate(['title' => 'NEW DROP — SS26'], [
            'subtitle' => 'Limited Edition',
            'placement' => 'hero',
            'is_active' => true,
            'sort_order' => 2,
            'link' => '/shop?sort=newest',
            'button_text' => 'Explore',
        ]);

        $products = [
            ['name' => 'Shadow Essential Hoodie', 'price' => 129.00, 'flags' => ['is_trending' => true, 'is_best_seller' => true]],
            ['name' => 'Midnight Cargo Pant', 'price' => 149.00, 'flags' => ['is_new_arrival' => true]],
            ['name' => 'Void Graphic Tee', 'price' => 79.00, 'flags' => ['is_trending' => true]],
            ['name' => 'Concrete Zip Hoodie', 'price' => 139.00, 'flags' => ['is_flash_sale' => true, 'flash_sale_price' => 99.00]],
            ['name' => 'Noir Oversized Tee', 'price' => 69.00, 'flags' => ['is_best_seller' => true]],
            ['name' => 'Urban Shell Jacket', 'price' => 199.00, 'flags' => ['is_new_arrival' => true, 'is_featured' => true]],
            ['name' => 'Stealth Jogger', 'price' => 119.00, 'flags' => []],
            ['name' => 'Archive Logo Hoodie', 'price' => 159.00, 'flags' => ['is_trending' => true, 'is_new_arrival' => true]],
        ];

        $unsplash = [
            'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800&q=80',
            'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800&q=80',
            'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80',
            'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&q=80',
        ];

        foreach ($products as $i => $data) {
            $slug = Str::slug($data['name']);
            $product = Product::firstOrCreate(['slug' => $slug], array_merge([
                'name' => $data['name'],
                'sku' => 'NC-'.strtoupper(Str::random(6)),
                'brand_id' => $brands->random()->id,
                'category_id' => collect([$hoodies->id, $tees->id, $pants->id])->random(),
                'short_description' => 'Premium heavyweight streetwear crafted for the Australian climate.',
                'description' => 'Built with premium materials and a relaxed luxury fit. Part of the NOIR Collective core range.',
                'price' => $data['price'],
                'compare_at_price' => $data['price'] * 1.2,
                'stock' => rand(10, 100),
                'is_active' => true,
                'available_sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'available_colors' => ['Black', 'Charcoal', 'Stone'],
                'size_guide' => "S: Chest 96cm\nM: Chest 104cm\nL: Chest 112cm\nXL: Chest 120cm",
                'shipping_info' => 'Free shipping on orders over $150. Standard delivery 3-7 business days across Australia.',
                'flash_sale_ends_at' => ($data['flags']['is_flash_sale'] ?? false) ? now()->addDays(3) : null,
            ], $data['flags']));

            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'path' => $unsplash[$i % count($unsplash)]],
                ['is_primary' => true, 'sort_order' => 0]
            );

            foreach (['S', 'M', 'L', 'XL'] as $size) {
                ProductVariant::firstOrCreate(
                    ['sku' => $product->sku.'-'.$size],
                    [
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => 'Black',
                        'color_hex' => '#000000',
                        'stock' => rand(5, 20),
                    ]
                );
            }
        }

        Coupon::firstOrCreate(['code' => 'NOIR10'], [
            'type' => 'percentage', 'value' => 10, 'min_order_amount' => 50,
            'is_active' => true, 'expires_at' => now()->addMonths(6),
        ]);

        Coupon::firstOrCreate(['code' => 'WELCOME20'], [
            'type' => 'fixed', 'value' => 20, 'min_order_amount' => 100,
            'is_active' => true,
        ]);

        Testimonial::firstOrCreate(['name' => 'Alex M.'], [
            'content' => 'The quality is insane. Best streetwear brand in Australia hands down.',
            'rating' => 5, 'role' => 'Verified Buyer', 'is_active' => true, 'sort_order' => 1,
        ]);

        Testimonial::firstOrCreate(['name' => 'Jordan K.'], [
            'content' => 'Premium feel, fast shipping. The hoodie is my daily uniform now.',
            'rating' => 5, 'role' => 'Melbourne', 'is_active' => true, 'sort_order' => 2,
        ]);

        Faq::firstOrCreate(['question' => 'What is your return policy?'], [
            'answer' => 'We offer 30-day returns on unworn items with tags attached.',
            'category' => 'shipping', 'is_active' => true,
        ]);

        $blogCat = BlogCategory::firstOrCreate(['slug' => 'culture'], ['name' => 'Culture']);
        Blog::firstOrCreate(['slug' => 'australian-streetwear-rise'], [
            'blog_category_id' => $blogCat->id,
            'title' => 'The Rise of Australian Streetwear',
            'excerpt' => 'How local brands are redefining luxury street culture down under.',
            'content' => '<p>Australian streetwear is having a moment. From Melbourne laneways to Sydney beaches, a new generation of brands is blending premium materials with underground aesthetics.</p>',
            'is_published' => true,
            'published_at' => now()->subDays(5),
            'meta_title' => 'Australian Streetwear Rise | NOIR Collective',
        ]);

        $igImages = [
            'https://images.unsplash.com/photo-1523381210434-271fa8a854e4?w=400&q=80',
            'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=400&q=80',
            'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=400&q=80',
            'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=400&q=80',
        ];
        foreach ($igImages as $i => $url) {
            InstagramPost::firstOrCreate(
                ['image' => $url],
                ['sort_order' => $i + 1, 'is_active' => true, 'link' => 'https://instagram.com']
            );
        }

        Setting::firstOrCreate(['key' => 'about_content'], [
            'group' => 'pages',
            'value' => 'NOIR Collective is a premium Australian streetwear brand born from underground culture and refined luxury.',
            'type' => 'text',
        ]);
    }
}
