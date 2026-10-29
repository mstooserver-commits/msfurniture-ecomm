<?php

namespace App\Livewire\Store;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ShopPage extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?string $category = null;

    #[Url]
    public ?string $subcategory = null;

    #[Url]
    public ?string $brand = null;

    #[Url]
    public array $sizes = [];

    #[Url]
    public array $colors = [];

    #[Url]
    public ?float $minPrice = null;

    #[Url]
    public ?float $maxPrice = null;

    #[Url]
    public string $availability = '';

    #[Url]
    public string $sort = 'newest';

    #[Url]
    public string $view = 'grid';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'category', 'subcategory', 'brand', 'sizes', 'colors', 'minPrice', 'maxPrice', 'availability', 'sort']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::active()->with(['images', 'brand', 'category']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('sku', 'like', "%{$this->search}%")
                    ->orWhere('short_description', 'like', "%{$this->search}%");
            });
        }

        if ($this->category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->category));
        }

        if ($this->subcategory) {
            $query->whereHas('subcategory', fn ($q) => $q->where('slug', $this->subcategory));
        }

        if ($this->brand) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $this->brand));
        }

        if ($this->minPrice) {
            $query->where('price', '>=', $this->minPrice);
        }

        if ($this->maxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }

        if ($this->availability === 'in_stock') {
            $query->where('stock', '>', 0);
        } elseif ($this->availability === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        }

        $query = match ($this->sort) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'popular' => $query->orderByDesc('sales_count'),
            'rating' => $query->orderByDesc('rating_avg'),
            default => $query->latest(),
        };

        return view('livewire.store.shop-page', [
            'products' => $query->paginate(12),
            'categories' => Category::active()->orderBy('name')->get(),
            'subcategories' => $this->category
                ? Subcategory::whereHas('category', fn ($q) => $q->where('slug', $this->category))->active()->get()
                : collect(),
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
        ])->layout('layouts.store');
    }
}
