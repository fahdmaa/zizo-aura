<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->text('product_image')->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order']);
            $table->index(['category_id', 'is_active']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->index(['is_read', 'created_at']);
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->index(['is_active', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_image')->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'sort_order']);
            $table->dropIndex(['category_id', 'is_active']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['is_read', 'created_at']);
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'expires_at']);
        });
    }
};
