<?php

namespace Tests\Feature\Store;

use App\Livewire\Store\ProductShow;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductInteractionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_select_a_finish_change_quantity_and_add_to_cart(): void
    {
        $product = Product::create([
            'name' => 'Milo Armchair',
            'slug' => 'milo-armchair',
            'sku' => 'MS-MILO-001',
            'price' => 980,
            'stock' => 10,
            'is_active' => true,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'MS-MILO-NAT',
            'color' => 'Natural',
            'stock' => 5,
            'is_active' => true,
        ]);

        Livewire::test(ProductShow::class, ['product' => $product])
            ->assertSet('variantId', $variant->id)
            ->call('incrementQuantity')
            ->assertSet('quantity', 2)
            ->call('addToCart')
            ->assertHasNoErrors()
            ->assertSet('cartMessage', 'Milo Armchair added to your cart.');

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'unit_price' => 980,
        ]);

        $this->assertSame(1, CartItem::count());
    }
}
