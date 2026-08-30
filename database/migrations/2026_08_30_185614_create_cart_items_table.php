<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();   // anonymous cart keyed by session
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('variant')->nullable();   // "100ml · Coco"
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);    // snapshot at add-to-cart time
            $table->timestamps();

            // One session can have many items but each (session + product + variant) is unique
            $table->unique(['session_id', 'product_id', 'variant']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
