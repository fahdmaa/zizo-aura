<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Pricing (in Moroccan Dirhams)
            $table->decimal('price', 10, 2);                     // original price
            $table->decimal('discounted_price', 10, 2)->nullable(); // null = no discount

            // Media
            $table->string('image');                             // main image
            $table->json('gallery')->nullable();                 // additional images

            // Badges / UI
            $table->boolean('is_new')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('in_stock')->default(true);
            $table->boolean('is_active')->default(true);

            // Stock quantity (null = unlimited / not tracked)
            $table->unsignedInteger('stock_quantity')->nullable();

            // Optional variant flags — real variants live in product_sizes/flavors
            $table->boolean('has_sizes')->default(false);
            $table->boolean('has_flavors')->default(false);

            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
