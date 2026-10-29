<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'vendor_id', 'brand_id', 'category_id', 'subcategory_id', 'name', 'slug', 'sku',
        'short_description', 'description', 'specifications', 'shipping_info', 'size_guide',
        'price', 'compare_at_price', 'cost_price', 'stock', 'low_stock_threshold',
        'available_sizes', 'available_colors', 'is_active', 'is_featured', 'is_trending',
        'is_new_arrival', 'is_best_seller', 'is_flash_sale', 'flash_sale_ends_at', 'flash_sale_price',
        'rating_avg', 'rating_count', 'views_count', 'sales_count', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'flash_sale_price' => 'decimal:2',
            'flash_sale_ends_at' => 'datetime',
            'available_sizes' => 'array',
            'available_colors' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_flash_sale' => 'boolean',
            'rating_avg' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function effectivePrice(): float
    {
        if ($this->is_flash_sale && $this->flash_sale_price && $this->flash_sale_ends_at?->isFuture()) {
            return (float) $this->flash_sale_price;
        }

        return (float) $this->price;
    }

    public function isOnSale(): bool
    {
        return $this->compare_at_price && $this->compare_at_price > $this->effectivePrice();
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ProductVideo::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
