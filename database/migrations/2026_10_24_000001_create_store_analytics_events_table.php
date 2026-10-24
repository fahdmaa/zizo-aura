<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 32);
            $table->char('visitor_hash', 64);
            $table->string('path', 255);
            $table->timestamp('created_at')->useCurrent();
            $table->index(['event_type', 'created_at']);
            $table->index(['visitor_hash', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_analytics_events');
    }
};
