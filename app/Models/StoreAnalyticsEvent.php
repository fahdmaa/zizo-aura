<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreAnalyticsEvent extends Model
{
    public const TYPES = ['page_view', 'store_click', 'whatsapp_order_click', 'web_checkout_click'];

    public $timestamps = false;

    protected $fillable = ['event_type', 'visitor_hash', 'path', 'created_at'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }
}
