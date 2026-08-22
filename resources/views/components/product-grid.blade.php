@props(['products' => [], 'categories' => []])

<section id="products" class="scroll-mt-24">
    <!-- Category Sticky Filters -->
    <div class="sticky top-16 z-30 bg-[var(--surface-raised)] border-b border-black/5 shadow-sm">
        <div class="container-custom">
            <div class="flex items-center gap-2 overflow-x-auto py-3.5 no-scrollbar">
                @foreach($categories as $index => $cat)
                    <button class="category-pill px-4 py-1.5 rounded-full text-xs transition-all duration-200 whitespace-nowrap {{ $index === 0 ? 'bg-[var(--terracotta)] text-white font-semibold' : 'border border-black/15 text-[var(--ink)] hover:border-black/30' }}"
                            data-category="{{ $cat['id'] }}">
                        {{ $cat['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Product Grid Content -->
    <div class="container-custom py-12 md:py-16">
        <!-- Section Header -->
        <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="display-md text-zinc-900 mb-1">Best-sellers</h2>
                <p id="product-count-display" class="text-xs md:text-sm text-[var(--ink-subtle)]">
                    {{ count($products) }} produits
                </p>
            </div>

            <!-- Sorting Dropdown -->
            <div class="flex items-center gap-2">
                <i data-lucide="sliders-horizontal" class="w-4 h-4 text-[var(--ink-muted)]"></i>
                <select id="sort-products-select" class="px-3 py-1.5 bg-white border border-black/15 rounded text-xs text-[var(--ink)] focus:outline-none focus:border-[var(--terracotta)] cursor-pointer">
                    <option value="popular">Popularité</option>
                    <option value="rating">Mieux notés</option>
                    <option value="price-asc">Prix croissant</option>
                    <option value="price-desc">Prix décroissant</option>
                </select>
            </div>
        </div>

        <!-- Dynamic Product Cards Grid -->
        <div id="products-grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <!-- Bottom Action CTA -->
        <div class="flex justify-center mt-12">
            <a href="#products" class="btn-primary">
                Voir tous les produits
            </a>
        </div>
    </div>
</section>
