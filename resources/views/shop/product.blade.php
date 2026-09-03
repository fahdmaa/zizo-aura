@extends('layouts.app')

@section('title', $product['name'] . ' — zizo aura')

@section('content')
<div class="w-full bg-white py-6 sm:py-10">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-zinc-400 mb-8 overflow-x-auto whitespace-nowrap">
            <a href="{{ route('home') }}" class="hover:text-black transition-colors">Accueil</a>
            <i class="ti ti-chevron-right text-[10px]"></i>
            <a href="{{ route('shop.index') }}" class="hover:text-black transition-colors">Boutique</a>
            <i class="ti ti-chevron-right text-[10px]"></i>
            <a href="{{ route('shop.index', ['category' => $product['category']]) }}" class="hover:text-black transition-colors">
                {{ $product['category_label'] }}
            </a>
            <i class="ti ti-chevron-right text-[10px]"></i>
            <span class="text-zinc-900 font-bold truncate max-w-[200px] sm:max-w-none">{{ $product['name'] }}</span>
        </nav>

@php
    $rawGallery = $product['gallery'] ?? [];
    if (is_string($rawGallery)) {
        $rawGallery = json_decode($rawGallery, true) ?: [];
    }
    $allImages = array_values(array_unique(array_filter(array_merge([$product['image']], (array) $rawGallery))));
    if (empty($allImages)) {
        $allImages = [$product['image'] ?: '/images/sdj_bum_bum_set.jpg'];
    }
@endphp

        <!-- Product Main Showcase Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start mb-20">
            
            <!-- Left: High-Res Product Image Gallery Showcase (Main Stage + Interactive Thumbnails) -->
            <div class="lg:col-span-6 sticky top-24 space-y-4" id="product-gallery-showcase">
                
                <!-- Main Stage Image Box -->
                <div class="relative aspect-square w-full bg-[#f8f9fa] rounded-3xl p-6 sm:p-10 overflow-hidden flex items-center justify-center border border-zinc-100 shadow-sm group select-none transition-all">
                    
                    <!-- Watermark Discount in Background -->
                    @if(!empty($product['discount']))
                        <div class="absolute inset-0 flex flex-col justify-between p-6 select-none pointer-events-none z-0">
                            <span class="watermark-discount text-zinc-200/70 text-left font-black tracking-tighter">
                                {{ $product['discount'] }}
                            </span>
                            <span class="watermark-discount text-zinc-200/70 text-right font-black tracking-tighter">
                                off
                            </span>
                        </div>
                    @endif

                    <!-- Floating Badge -->
                    @if(!empty($product['badge']))
                        <div class="absolute top-4 left-4 z-20">
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider shadow-sm {{ $product['badge_color'] }}">
                                {{ $product['badge'] }}
                            </span>
                        </div>
                    @endif

                    <!-- Photos Counter Badge (when multiple images) -->
                    @if(count($allImages) > 1)
                        <div class="absolute top-4 right-4 z-20">
                            <span id="gallery-counter-badge" class="px-3 py-1 rounded-full bg-black/70 backdrop-blur-md text-white text-[11px] font-extrabold shadow-sm flex items-center gap-1">
                                <i class="ti ti-photo text-xs"></i>
                                <span><span id="gallery-current-idx">1</span> / {{ count($allImages) }}</span>
                            </span>
                        </div>
                    @endif

                    <!-- Main Stage Image with Smooth Transition -->
                    <img id="product-main-stage-img"
                         src="{{ $allImages[0] }}"
                         alt="{{ $product['name'] }}"
                         data-current-index="0"
                         class="relative z-10 w-full h-full object-contain max-h-[380px] sm:max-h-[440px] group-hover:scale-105 transition-all duration-300 ease-out cursor-zoom-in" />

                    <!-- Prev / Next Navigation Overlay Buttons (when multiple images) -->
                    @if(count($allImages) > 1)
                        <button type="button"
                                id="gallery-prev-btn"
                                aria-label="Photo précédente"
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-zinc-800 shadow-md border border-zinc-200/80 flex items-center justify-center cursor-pointer transition-all hover:scale-110 hover:text-pink-600 opacity-90 sm:opacity-0 group-hover:opacity-100">
                            <i class="ti ti-chevron-left text-lg font-bold"></i>
                        </button>
                        <button type="button"
                                id="gallery-next-btn"
                                aria-label="Photo suivante"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-zinc-800 shadow-md border border-zinc-200/80 flex items-center justify-center cursor-pointer transition-all hover:scale-110 hover:text-pink-600 opacity-90 sm:opacity-0 group-hover:opacity-100">
                            <i class="ti ti-chevron-right text-lg font-bold"></i>
                        </button>
                    @endif
                </div>

                <!-- Interactive Thumbnails Carousel / Strip (when multiple images) -->
                @if(count($allImages) > 1)
                    <div class="relative">
                        <div id="gallery-thumbs-track" class="flex items-center gap-3 overflow-x-auto custom-scrollbar pb-2 pt-1 px-1 scroll-smooth">
                            @foreach($allImages as $idx => $imgUrl)
                                <button type="button"
                                        class="gallery-thumb-btn relative w-20 h-20 sm:w-22 sm:h-22 rounded-2xl bg-[#f8f9fa] border-2 p-1.5 shrink-0 overflow-hidden cursor-pointer transition-all duration-200 group {{ $idx === 0 ? 'border-pink-500 ring-2 ring-pink-500/20 shadow-sm scale-105 opacity-100' : 'border-zinc-200 hover:border-pink-300 opacity-70 hover:opacity-100' }}"
                                        data-image-src="{{ $imgUrl }}"
                                        data-index="{{ $idx }}"
                                        aria-label="Afficher la photo {{ $idx + 1 }}">
                                    <img src="{{ $imgUrl }}"
                                         alt="{{ $product['name'] }} — Vue {{ $idx + 1 }}"
                                         class="w-full h-full object-contain transition-transform group-hover:scale-105" />
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Right: Product Purchase Options & Details -->
            <div class="lg:col-span-6 flex flex-col">
                
                <!-- Category Tag -->
                <span class="text-xs font-extrabold uppercase tracking-widest text-pink-600 mb-2">
                    {{ $product['category_label'] }}
                </span>

                <!-- Title & Subtitle -->
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black tracking-tight leading-tight mb-2">
                    {{ $product['name'] }}
                </h1>
                <p class="text-sm text-zinc-500 font-medium mb-4">
                    {{ $product['subtitle'] }}
                </p>

                <!-- Reviews Rating Bar -->
                <div class="flex items-center gap-2 mb-6 pb-6 border-b border-zinc-100">
                    <div class="flex items-center text-amber-400">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ti ti-star-filled text-base {{ $i <= round($product['rating']) ? 'text-amber-400' : 'text-zinc-200' }}"></i>
                        @endfor
                    </div>
                    <span class="text-xs font-bold text-zinc-900">{{ $product['rating'] }} / 5</span>
                    <span class="text-xs text-zinc-400">({{ $product['review_count'] }} avis vérifiés)</span>
                </div>

                <!-- Price Box -->
                <div class="flex items-baseline gap-3 mb-8">
                    <span class="text-3xl sm:text-4xl font-black text-pink-600 tracking-tight">
                        {{ $product['price'] }} DH
                    </span>
                    @if(!empty($product['discount']) && !empty($product['original_price']) && $product['original_price'] !== $product['price'])
                        <span class="text-lg font-bold text-zinc-400 line-through">
                            {{ $product['original_price'] }} DH
                        </span>
                        <span class="px-2.5 py-1 rounded-md bg-pink-50 text-pink-600 text-xs font-extrabold uppercase tracking-wider">
                            Remise de {{ $product['discount'] }}
                        </span>
                    @endif
                </div>

                <!-- Scent / Flavor Switcher (if available) -->
                @if(!empty($product['flavors']))
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-2.5">
                            Fragrance sélectionnée : <span id="selected-flavor-label" class="text-pink-600 font-extrabold ml-1">{{ $product['flavors'][0]['name'] }}</span>
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product['flavors'] as $fIndex => $flavor)
                                <button type="button"
                                        class="flavor-swatch-btn px-4 py-2 rounded-full border text-xs font-bold flex items-center gap-2 transition-all cursor-pointer {{ $fIndex === 0 ? 'border-black bg-zinc-900 text-white shadow-sm ring-2 ring-black/10' : 'border-zinc-200 bg-white text-zinc-700 hover:border-zinc-400' }}"
                                        data-flavor-name="{{ $flavor['name'] }}">
                                    <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $flavor['color'] }};"></span>
                                    <span>{{ $flavor['name'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Sizes / Formats Selector -->
                @if(!empty($product['sizes']))
                    <div class="mb-8">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-2.5">
                            Format sélectionné : <span id="selected-size-label" class="text-pink-600 font-extrabold ml-1">{{ $product['sizes'][0] }}</span>
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product['sizes'] as $sIndex => $size)
                                <button type="button"
                                        class="size-option-btn px-4 py-2 rounded-full border text-xs font-bold transition-all cursor-pointer {{ $sIndex === 0 ? 'border-black bg-zinc-900 text-white shadow-sm ring-2 ring-black/10' : 'border-zinc-200 text-zinc-700 hover:border-zinc-400 bg-white' }}"
                                        data-size-name="{{ $size }}">
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quantity Selector + Add to Cart Row (Compact & Clean) -->
                <div class="space-y-4 mb-8">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        
                        <!-- Number of Units Wanted Pill (Compact with no browser spinners) -->
                        <div class="flex items-center justify-between bg-[#f8f9fa] border border-zinc-200 rounded-full px-2 py-1 h-11 w-full sm:w-28 shrink-0 shadow-2xs">
                            <button type="button"
                                    id="qty-minus-btn"
                                    aria-label="Diminuer la quantité"
                                    class="btn-circle-action w-7 h-7 bg-white hover:bg-zinc-200 text-zinc-800 flex items-center justify-center disabled:opacity-25 disabled:cursor-not-allowed cursor-pointer shadow-2xs">
                                <i class="ti ti-minus text-xs font-bold"></i>
                            </button>

                            <span id="product-quantity-display" class="w-8 sm:w-6 text-center font-extrabold text-sm text-zinc-900 select-none">
                                1
                            </span>
                            <input type="hidden" id="product-quantity-input" name="quantity" value="1" />

                            <button type="button"
                                    id="qty-plus-btn"
                                    aria-label="Augmenter la quantité"
                                    class="btn-circle-action w-7 h-7 bg-white hover:bg-zinc-200 text-zinc-800 flex items-center justify-center cursor-pointer shadow-2xs">
                                <i class="ti ti-plus text-xs font-bold"></i>
                            </button>
                        </div>

                        <!-- Compact Add to Cart Button -->
                        <button id="product-add-cart-btn"
                                type="button"
                                class="btn-card-pill h-11 px-5 sm:px-6 py-2 text-xs sm:text-sm font-extrabold uppercase tracking-wider flex-1 flex items-center justify-center gap-2 shadow-sm"
                                data-product-name="{{ $product['name'] }}"
                                data-unit-price="{{ $product['raw_price'] ?? $product['price'] }}"
                                data-product-price="{{ $product['price'] }}"
                                data-product-image="{{ $product['image'] }}"
                                data-product-slug="{{ $product['slug'] }}">
                            <i class="ti ti-shopping-bag-plus text-base"></i>
                            <span id="btn-cart-text">Ajouter au panier &bull; {{ $product['price'] }} DH</span>
                        </button>
                    </div>

                    <p class="text-left text-[11px] text-zinc-400 font-semibold flex items-center gap-1.5">
                        <i class="ti ti-truck text-sm text-emerald-500"></i>
                        <span>Livraison offerte dès 550 DH &bull; Expédié sous 24h &bull; Paiement sécurisé</span>
                    </p>
                </div>

                <!-- Product Information Tabs Accordions -->
                <div class="border-t border-zinc-200 divide-y divide-zinc-200">
                    
                    <!-- Description -->
                    <details class="group py-4" open>
                        <summary class="flex justify-between items-center cursor-pointer font-bold text-xs uppercase tracking-wider text-zinc-900 list-none">
                            <span>Description &amp; Résultats</span>
                            <i class="ti ti-plus group-open:hidden text-zinc-500"></i>
                            <i class="ti ti-minus hidden group-open:block text-zinc-500"></i>
                        </summary>
                        <div class="pt-3 text-xs sm:text-sm text-zinc-600 leading-relaxed">
                            {{ $product['description'] }}
                        </div>
                    </details>

                    <!-- Ingrédients -->
                    <details class="group py-4">
                        <summary class="flex justify-between items-center cursor-pointer font-bold text-xs uppercase tracking-wider text-zinc-900 list-none">
                            <span>Ingrédients Clés &amp; Bienfaits</span>
                            <i class="ti ti-plus group-open:hidden text-zinc-500"></i>
                            <i class="ti ti-minus hidden group-open:block text-zinc-500"></i>
                        </summary>
                        <div class="pt-3 text-xs sm:text-sm text-zinc-600 leading-relaxed">
                            {{ $product['ingredients'] }}
                        </div>
                    </details>

                    <!-- Notes Olfactives -->
                    <details class="group py-4">
                        <summary class="flex justify-between items-center cursor-pointer font-bold text-xs uppercase tracking-wider text-zinc-900 list-none">
                            <span>Pyramide Olfactive</span>
                            <i class="ti ti-plus group-open:hidden text-zinc-500"></i>
                            <i class="ti ti-minus hidden group-open:block text-zinc-500"></i>
                        </summary>
                        <div class="pt-3 text-xs sm:text-sm text-zinc-600 leading-relaxed">
                            {{ $product['olfactory'] }}
                        </div>
                    </details>

                    <!-- Conseil d'utilisation -->
                    <details class="group py-4">
                        <summary class="flex justify-between items-center cursor-pointer font-bold text-xs uppercase tracking-wider text-zinc-900 list-none">
                            <span>Conseils d'Application</span>
                            <i class="ti ti-plus group-open:hidden text-zinc-500"></i>
                            <i class="ti ti-minus hidden group-open:block text-zinc-500"></i>
                        </summary>
                        <div class="pt-3 text-xs sm:text-sm text-zinc-600 leading-relaxed">
                            {{ $product['usage'] }}
                        </div>
                    </details>

                </div>

            </div>

        </div>

        <!-- Related Products Section -->
        @if(count($relatedProducts) > 0)
            <div class="pt-16 border-t border-zinc-100">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight mb-8">
                    Vous aimerez aussi
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedProducts as $rel)
                        <div class="product-card group flex flex-col bg-white rounded-2xl p-3 sm:p-4 border border-zinc-100 shadow-[0_2px_10px_rgba(0,0,0,0.03)] hover:shadow-[0_12px_30px_rgba(0,0,0,0.08)] transition-all duration-300">
                            <a href="{{ route('shop.product', $rel['slug']) }}" class="relative aspect-square w-full bg-[#f8f9fa] rounded-xl overflow-hidden flex items-center justify-center p-6 mb-4 block">
                                @if(!empty($rel['discount']))
                                    <!-- Watermark Typography Discount -->
                                    <div class="absolute inset-0 flex flex-col justify-between p-3 select-none pointer-events-none z-0">
                                        <span class="watermark-discount text-zinc-200/70 text-left font-black tracking-tighter">
                                            {{ $rel['discount'] }}
                                        </span>
                                        <span class="watermark-discount text-zinc-200/70 text-right font-black tracking-tighter">
                                            off
                                        </span>
                                    </div>
                                @endif

                                @if(!empty($rel['badge']))
                                    <!-- Floating Pill Badge -->
                                    <div class="absolute top-2.5 inset-x-0 flex justify-center z-20">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider shadow-sm {{ $rel['badge_color'] }}">
                                            {{ $rel['badge'] }}
                                        </span>
                                    </div>
                                @endif

                                <img src="{{ $rel['image'] }}"
                                     alt="{{ $rel['name'] }}"
                                     class="relative z-10 w-full h-full object-contain max-h-[180px] group-hover:scale-105 transition-transform duration-500 ease-out" />
                            </a>

                            <div class="flex flex-col items-center text-center flex-1">
                                <!-- Rating Stars -->
                                <div class="flex items-center gap-1 text-amber-400 text-xs mb-1.5">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ti ti-star-filled text-[11px] {{ $i <= round($rel['rating'] ?? 5) ? 'text-amber-400' : 'text-zinc-200' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] font-bold text-zinc-400">({{ $rel['review_count'] ?? 100 }})</span>
                                </div>

                                <h3 class="text-sm font-bold text-zinc-900 leading-snug mb-1 min-h-[40px] flex items-center justify-center">
                                    <a href="{{ route('shop.product', $rel['slug']) }}" class="hover:text-pink-600 transition-colors">
                                        {{ $rel['name'] }}
                                    </a>
                                </h3>

                                <div class="flex items-center justify-center gap-2 mb-4">
                                    <span class="text-base sm:text-lg font-extrabold text-pink-600">{{ $rel['price'] }} DH</span>
                                    <span class="text-xs text-zinc-400 line-through">{{ $rel['original_price'] }} DH</span>
                                </div>

                                <button class="btn-card-pill w-full mt-auto"
                                        data-add-to-cart
                                        data-product-name="{{ $rel['name'] }}"
                                        data-product-price="{{ $rel['price'] }}"
                                        data-product-image="{{ $rel['image'] }}"
                                        data-product-slug="{{ $rel['slug'] }}">
                                    <i class="ti ti-shopping-bag-plus text-base"></i>
                                    <span>Ajouter au panier</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

<!-- Mobile Sticky Purchase Bottom Bar (High Conversion Mobile Element) -->
<div id="mobile-sticky-buy-bar" class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-zinc-200/80 p-3 z-40 sm:hidden shadow-[0_-8px_30px_rgba(0,0,0,0.08)] transform translate-y-full transition-transform duration-300 ease-out flex items-center justify-between gap-3 pb-[calc(0.75rem+env(safe-area-inset-bottom,0px))]">
    <div class="flex items-center gap-2.5 min-w-0">
        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-10 h-10 rounded-xl bg-zinc-50 border border-zinc-100 object-contain p-0.5 shrink-0" />
        <div class="truncate">
            <h4 class="text-xs font-bold text-zinc-900 truncate leading-tight">{{ $product['name'] }}</h4>
            <div class="flex items-center gap-1.5 mt-0.5">
                <span class="text-xs font-black text-pink-600">{{ $product['price'] }} DH</span>
                @if(!empty($product['discount']) && !empty($product['original_price']) && $product['original_price'] !== $product['price'])
                    <span class="text-[10px] font-bold text-zinc-400 line-through">{{ $product['original_price'] }} DH</span>
                @endif
            </div>
        </div>
    </div>
    <button type="button"
            id="mobile-sticky-add-btn"
            class="btn-card-pill py-2.5 px-4 text-xs font-extrabold uppercase tracking-wider shrink-0 shadow-sm">
        <i class="ti ti-shopping-bag-plus text-sm"></i>
        <span>Ajouter</span>
    </button>
</div>

<!-- Full-Screen High-Res Lightbox Visualizer Modal -->
<div id="product-lightbox-modal" class="fixed inset-0 z-50 bg-black/95 backdrop-blur-xl flex items-center justify-center p-4 sm:p-8 opacity-0 pointer-events-none transition-all duration-300 select-none">
    <button type="button" id="lightbox-close-btn" class="absolute top-5 right-5 w-11 h-11 rounded-full bg-white/15 hover:bg-white text-white hover:text-black flex items-center justify-center text-xl transition-all cursor-pointer z-50 shadow-lg">
        <i class="ti ti-x"></i>
    </button>

    <button type="button" id="lightbox-prev-btn" class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/15 hover:bg-white text-white hover:text-black flex items-center justify-center text-xl transition-all cursor-pointer z-50 shadow-lg">
        <i class="ti ti-chevron-left"></i>
    </button>

    <div class="relative max-w-4xl max-h-[85vh] w-full flex items-center justify-center p-2">
        <img id="lightbox-main-img" src="{{ $allImages[0] }}" alt="{{ $product['name'] }}" class="max-w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl transition-all duration-300" />
    </div>

    <button type="button" id="lightbox-next-btn" class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/15 hover:bg-white text-white hover:text-black flex items-center justify-center text-xl transition-all cursor-pointer z-50 shadow-lg">
        <i class="ti ti-chevron-right"></i>
    </button>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 px-4 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-white text-xs font-black tracking-wide">
        <span id="lightbox-counter">1 / {{ count($allImages) }}</span>
    </div>
</div>
@endsection
