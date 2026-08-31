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

        <!-- Product Main Showcase Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start mb-20">
            
            <!-- Left: High-Res Product Image Gallery -->
            <div class="lg:col-span-6 sticky top-24">
                <div class="relative aspect-square w-full bg-[#f8f9fa] rounded-3xl p-8 sm:p-12 overflow-hidden flex items-center justify-center border border-zinc-100 shadow-sm group">
                    
                    <!-- Watermark Discount in Background -->
                    <div class="absolute inset-0 flex flex-col justify-between p-6 select-none pointer-events-none z-0">
                        <span class="watermark-discount text-zinc-200/70 text-left font-black tracking-tighter">
                            {{ $product['discount'] }}
                        </span>
                        <span class="watermark-discount text-zinc-200/70 text-right font-black tracking-tighter">
                            off
                        </span>
                    </div>

                    <!-- Floating Badge -->
                    <div class="absolute top-4 left-4 z-20">
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider shadow-sm {{ $product['badge_color'] }}">
                            {{ $product['badge'] }}
                        </span>
                    </div>

                    <!-- Product Image Asset -->
                    <img src="{{ $product['image'] }}"
                         alt="{{ $product['name'] }}"
                         class="relative z-10 w-full h-full object-contain max-h-[380px] sm:max-h-[440px] group-hover:scale-105 transition-transform duration-700 ease-out" />
                </div>
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
                    <span class="text-lg font-bold text-zinc-400 line-through">
                        {{ $product['original_price'] }} DH
                    </span>
                    <span class="px-2.5 py-1 rounded-md bg-pink-50 text-pink-600 text-xs font-extrabold uppercase tracking-wider">
                        Remise de {{ $product['discount'] }}
                    </span>
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
                    <div class="flex items-center gap-3">
                        
                        <!-- Number of Units Wanted Pill (Compact with no browser spinners) -->
                        <div class="flex items-center justify-between bg-[#f8f9fa] border border-zinc-200 rounded-full px-1.5 py-1 h-11 w-28 shrink-0 shadow-2xs">
                            <button type="button"
                                    id="qty-minus-btn"
                                    aria-label="Diminuer la quantité"
                                    class="btn-circle-action w-7 h-7 bg-white hover:bg-zinc-200 text-zinc-800 flex items-center justify-center disabled:opacity-25 disabled:cursor-not-allowed cursor-pointer shadow-2xs">
                                <i class="ti ti-minus text-xs font-bold"></i>
                            </button>

                            <span id="product-quantity-display" class="w-6 text-center font-extrabold text-sm text-zinc-900 select-none">
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
                                class="btn-card-pill h-11 px-5 sm:px-6 py-2 text-xs sm:text-sm font-extrabold uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm"
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
@endsection
