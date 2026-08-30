<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'city',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'total',
        'coupon_code',
        'status',
        'notes',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Status helpers ────────────────────────────────────────────────────────

    public function isCancelled(): bool  { return $this->status === 'cancelled'; }
    public function isDelivered(): bool  { return $this->status === 'delivered'; }
    public function isPending(): bool    { return $this->status === 'pending'; }

    /**
     * Human-readable status badge label (French).
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'En attente',
            'confirmed'  => 'Confirmée',
            'processing' => 'En préparation',
            'shipped'    => 'Expédiée',
            'delivered'  => 'Livrée',
            'cancelled'  => 'Annulée',
            default      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'yellow',
            'confirmed'  => 'blue',
            'processing' => 'indigo',
            'shipped'    => 'purple',
            'delivered'  => 'green',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }
}
