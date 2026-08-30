<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();                   // e.g. "SUMMER20"
            $table->enum('type', ['percent', 'fixed']);         // % off or DH off
            $table->decimal('value', 10, 2);                    // 20 → 20% or 20 DH
            $table->decimal('min_order_amount', 10, 2)->default(0); // minimum cart total
            $table->unsignedInteger('max_uses')->nullable();    // null = unlimited
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
