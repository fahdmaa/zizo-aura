<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('name');
            $table->string('badge')->nullable()->after('gallery');
            $table->string('badge_color')->nullable()->after('badge');
            $table->decimal('rating', 2, 1)->nullable()->after('badge_color');
            $table->unsignedInteger('review_count')->default(0)->after('rating');
            $table->text('ingredients')->nullable()->after('description');
            $table->text('olfactory')->nullable()->after('ingredients');
            $table->text('usage')->nullable()->after('olfactory');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'badge', 'badge_color', 'rating', 'review_count', 'ingredients', 'olfactory', 'usage']);
        });
    }
};
