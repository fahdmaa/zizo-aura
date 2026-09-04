<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'subtitle',
        'slug',
        'description',
        'ingredients',
        'olfactory',
        'usage',
        'price',
        'discounted_price',
        'image',
        'gallery',
        'badge',
        'badge_color',
        'rating',
        'review_count',
        'is_new',
        'is_bestseller',
        'in_stock',
        'is_active',
        'stock_quantity',
        'has_sizes',
        'has_flavors',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'gallery' => 'array',
        'rating' => 'decimal:1',
        'review_count' => 'integer',
        'is_new' => 'boolean',
        'is_bestseller' => 'boolean',
        'in_stock' => 'boolean',
        'is_active' => 'boolean',
        'stock_quantity' => 'integer',
        'has_sizes' => 'boolean',
        'has_flavors' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class)->orderBy('sort_order');
    }

    public function flavors(): HasMany
    {
        return $this->hasMany(ProductFlavor::class)->orderBy('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // ── Computed ──────────────────────────────────────────────────────────────

    /**
     * Effective selling price in DH.
     */
    public function getEffectivePriceAttribute(): string
    {
        return $this->discounted_price ?? $this->price;
    }

    /**
     * Discount percentage, e.g. 20 for 20%.
     */
    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->discounted_price || (float) $this->price <= 0) {
            return null;
        }

        return (int) round((1 - (float) $this->discounted_price / (float) $this->price) * 100);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('in_stock', true)
            ->where(fn ($q) => $q->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0));
    }

    /** The stable shape consumed by the public storefront and its API. */
    public function toStorefrontArray(): array
    {
        $price = (float) $this->price;
        $salePrice = $this->discounted_price === null ? null : (float) $this->discounted_price;
        $sizes = $this->relationLoaded('sizes') ? $this->sizes : collect();
        $flavors = $this->relationLoaded('flavors') ? $this->flavors : collect();

        // Resolve badge & badge color if not explicitly defined
        $badge = $this->badge;
        $badgeColor = $this->badge_color;

        if (empty($badge)) {
            if ($this->is_bestseller) {
                $badge = 'Best-Seller';
                $badgeColor = $badgeColor ?: 'bg-rose-500 text-white';
            } elseif ($this->is_new) {
                $badge = 'Nouveau';
                $badgeColor = $badgeColor ?: 'bg-black text-white';
            }
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'brand' => $this->category?->name,
            'name' => $this->name,
            'subtitle' => $this->subtitle ?? '',
            'discount' => $this->discount_percent ? '-'.$this->discount_percent.'%' : null,
            'badge' => $badge,
            'badge_color' => $badgeColor ?? 'bg-pink-500 text-white',
            'price' => (string) ($salePrice ?? $price),
            'original_price' => (string) $price,
            'raw_price' => $salePrice ?? $price,
            'image' => $this->image,
            'gallery' => $this->gallery ?? [],
            'category' => $this->category?->slug,
            'category_label' => $this->category?->name,
            'rating' => (float) ($this->rating ?? 0),
            'review_count' => $this->review_count,
            'sizes' => $sizes->map(fn (ProductSize $size) => $size->label)->values()->all(),
            'size_options' => $sizes->map(fn (ProductSize $size) => ['id' => $size->id, 'label' => $size->label, 'price' => $size->price, 'in_stock' => $size->in_stock])->values()->all(),
            'flavors' => $flavors->map(fn (ProductFlavor $flavor) => ['id' => $flavor->id, 'name' => $flavor->label, 'color' => $flavor->color_hex, 'in_stock' => $flavor->in_stock])->values()->all(),
            'description' => $this->description,
            'ingredients' => $this->ingredients,
            'olfactory' => $this->olfactory,
            'usage' => $this->usage,
            'in_stock' => $this->in_stock && ($this->stock_quantity === null || $this->stock_quantity > 0),
        ];
    }
}
