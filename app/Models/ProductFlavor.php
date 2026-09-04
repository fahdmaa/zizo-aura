<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFlavor extends Model
{
    protected $fillable = ['product_id', 'label', 'color_hex', 'in_stock', 'sort_order'];

    protected $casts = [
        'in_stock' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
