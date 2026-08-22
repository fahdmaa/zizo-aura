@props(['product'])

<article class="product-card-item group relative flex flex-col bg-white rounded-md overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl shadow-[0_1px_4px_oklch(0.15_0.02_30_/_0.06)] border border-black/5"
         data-id="{{ $product['id'] }}"
         data-category="{{ $product['category'] }}"
         data-price="{{ $product['price'] }}"
         data-rating="{{ $product['rating'] }}"
         data-badge="{{ $product['badge'] ?? '' }}"
         data-is-new="{{ $product['is_new'] ? '1' : '0' }}">

    <!-- Image Area with Hover-Swap -->
    <div class="relative aspect-[3/4] overflow-hidden bg-[var(--sable)]">
        <!-- Main Image -->
        <img src="{{ $product['image'] }}"
             alt="{{ $product['name'] }}"
             class="absolute inset-0 w-full h-full object-cover transition-all duration-500 group-hover:opacity-0 group-hover:scale-105" />

        <!-- Hover Image -->
        <img src="{{ $product['hover_image'] }}"
             alt="{{ $product['name'] }}"
             aria-hidden="true"
             class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-all duration-500 group-hover:scale-105" />

        <!-- Badge -->
        @if(!empty($product['badge']))
            <div class="absolute top-3 left-3">
                <span class="badge badge--{{ $product['badge'] }}">
                    {{ $product['badge_label'] }}
                </span>
            </div>
        @endif

        <!-- Wishlist Button -->
        <button class="btn-wishlist absolute top-3 right-3 w-9 h-9 rounded-full bg-white flex items-center justify-center text-[var(--ink-subtle)] shadow-md transition-transform active:scale-90 hover:text-[var(--terracotta)]"
                aria-label="Ajouter aux favoris"
                data-wished="false">
            <i data-lucide="heart" class="w-4 h-4"></i>
        </button>

        <!-- Quick Add to Cart Drawer Button on Hover -->
        <div class="absolute bottom-0 inset-x-0 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out">
            <button class="btn-add-cart w-full py-3.5 bg-[var(--ink)] hover:bg-[var(--terracotta)] text-white text-xs font-semibold tracking-widest uppercase flex items-center justify-center gap-2 transition-colors duration-200"
                    data-product-name="{{ $product['name'] }}"
                    data-product-price="{{ $product['price'] }}">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                <span>Ajouter au panier</span>
            </button>
        </div>
    </div>

    <!-- Product Details -->
    <div class="p-4 md:p-5 flex flex-col flex-1">
        <!-- Brand Label -->
        <span class="text-[10px] font-bold tracking-widest uppercase text-[var(--terracotta)] mb-1">
            Sol de Janeiro
        </span>

        <!-- Product Title -->
        <h3 class="font-serif text-lg font-medium text-zinc-900 leading-snug mb-1 line-clamp-1">
            {{ $product['name'] }}
        </h3>

        <!-- Description -->
        <p class="text-xs text-[var(--ink-subtle)] leading-relaxed mb-3 line-clamp-2">
            {{ $product['description'] }}
        </p>

        <!-- Star Rating -->
        <div class="flex items-center gap-1.5 mb-3">
            <div class="flex items-center text-[var(--or)]">
                @for($i = 1; $i <= 5; $i++)
                    <i data-lucide="star" class="w-3 h-3 {{ $i <= round($product['rating']) ? 'fill-current' : 'text-zinc-300' }}"></i>
                @endfor
            </div>
            <span class="text-[11px] text-[var(--ink-subtle)] font-medium">
                {{ $product['rating'] }} ({{ number_format($product['review_count'], 0, ',', ' ') }})
            </span>
        </div>

        <!-- Available Sizes -->
        <div class="flex flex-wrap gap-1.5 mb-4">
            @foreach($product['sizes'] as $size)
                <span class="px-2 py-0.5 border border-zinc-200 rounded text-[11px] text-[var(--ink-muted)] bg-zinc-50 font-medium">
                    {{ $size }}
                </span>
            @endforeach
        </div>

        <!-- Price -->
        <div class="mt-auto flex items-baseline gap-2 pt-2 border-t border-zinc-100">
            <span class="text-lg font-bold text-zinc-900">
                {{ $product['price'] }} DH
            </span>
            @if(!empty($product['price_per_unit']))
                <span class="text-xs text-[var(--ink-subtle)]">
                    {{ $product['price_per_unit'] }}
                </span>
            @endif
        </div>
    </div>
</article>
