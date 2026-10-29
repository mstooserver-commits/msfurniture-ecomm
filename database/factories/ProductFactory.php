<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'sku' => 'NC-'.strtoupper(Str::random(8)),
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->randomFloat(2, 49, 299),
            'compare_at_price' => fake()->randomFloat(2, 59, 349),
            'stock' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
