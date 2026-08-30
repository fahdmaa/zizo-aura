<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'used_count',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeValid($query)
    {
        return $query
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->where(fn ($q) => $q->whereNull('max_uses')->orWhereColumn('used_count', '<', 'max_uses'));
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Calculate the discount amount (DH) for a given subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            return round($subtotal * $this->value / 100, 2);
        }

        return min((float) $this->value, $subtotal);
    }

    public function appliesTo(float $subtotal): bool
    {
        return $this->is_active
            && ! $this->isExpired()
            && ! $this->isExhausted()
            && $subtotal >= (float) $this->min_order_amount;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isExhausted(): bool
    {
        return $this->max_uses && $this->used_count >= $this->max_uses;
    }
}
