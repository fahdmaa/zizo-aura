@extends('layouts.app')

@section('title', 'Boutique Officielle — zizo aura')

@section('content')
<div class="w-full bg-white py-8 sm:py-12">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Shop Header Banner -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-zinc-100 mb-8">
            <div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black tracking-tight leading-tight">
                    Nos Formules &amp; Soins Solaires
                </h1>
                <p class="text-sm sm:text-base text-zinc-500 font-normal mt-2 max-w-xl">
                    Découvrez nos brumes Cheirosa emblématiques, crèmes raffermissantes et élixirs botaniques formulés pour sublimer chaque peau.
                </p>
            </div>

            <!-- Total Count & Custom Themed Sort Dropdown -->
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">
                    {{ count($products) }} produit{{ count($products) > 1 ? 's' : '' }}
                </span>

                <!-- Custom Theme Dropdown Menu -->
                <div class="relative group" id="custom-sort-dropdown">
                    <button type="button"
                            class="px-4 py-2 bg-white hover:bg-pink-50/50 border border-zinc-200 hover:border-pink-300 rounded-full text-xs font-bold text-zinc-800 uppercase tracking-wider focus:outline-none transition-all flex items-center gap-2 cursor-pointer shadow-xs">
                        <span class="text-zinc-900 font-extrabold">
                            @if($sortBy === 'rating') Mieux notés
                            @elseif($sortBy === 'price-asc') Prix croissant
                            @elseif($sortBy === 'price-desc') Prix décroissant
                            @else Popularité
                            @endif
                        </span>
                        <i class="ti ti-chevron-down text-xs text-zinc-400 group-hover:rotate-180 transition-transform duration-200"></i>
                    </button>

                    <!-- Custom Floating Dropdown Panel -->
                    <div class="absolute right-0 top-full mt-2 w-52 bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.12)] border border-zinc-100 p-1.5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="{{ route('shop.index', ['category' => $selectedCategory, 'sort' => 'popular']) }}"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'popular' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Popularité</span>
                            @if($sortBy === 'popular') <i class="ti ti-check text-xs"></i> @endif
                        </a>
                        <a href="{{ route('shop.index', ['category' => $selectedCategory, 'sort' => 'rating']) }}"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'rating' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Mieux notés</span>
                            @if($sortBy === 'rating') <i class="ti ti-check text-xs"></i> @endif
                        </a>
                        <a href="{{ route('shop.index', ['category' => $selectedCategory, 'sort' => 'price-asc']) }}"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'price-asc' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Prix croissant</span>
                            @if($sortBy === 'price-asc') <i class="ti ti-check text-xs"></i> @endif
                        </a>
                        <a href="{{ route('shop.index', ['category' => $selectedCategory, 'sort' => 'price-desc']) }}"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'price-desc' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Prix décroissant</span>
                            @if($sortBy === 'price-desc') <i class="ti ti-check text-xs"></i> @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Filter Pills Bar -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 no-scrollbar">
            @foreach($categories as $cat)
                <a href="{{ route('shop.index', array_filter(['category' => $cat['slug'], 'sort' => $sortBy, 'q' => $searchQuery ?? null])) }}"
                   class="px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider whitespace-nowrap transition-all duration-200 {{ $selectedCategory === $cat['slug'] ? 'bg-black text-white shadow-md' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200' }}">
                    {{ $cat['name'] }}
                </a>
            @endforeach
        </div>

        <!-- Active Search Filter Banner -->
        @if(!empty($searchQuery))
            <div class="mb-8 p-4 bg-pink-50/70 border border-pink-200/80 rounded-2xl flex flex-wrap items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-pink-600 text-white flex items-center justify-center text-base shrink-0 shadow-xs">
                        <i class="ti ti-search"></i>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-bold text-zinc-900">
                            Résultats pour : <span class="text-pink-600 font-extrabold">&laquo; {{ $searchQuery }} &raquo;</span>
                        </p>
                        <p class="text-[11px] text-zinc-500 font-medium">
                            {{ count($products) }} résultat{{ count($products) > 1 ? 's trouvés' : ' trouvé' }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('shop.index', ['category' => $selectedCategory !== 'all' ? $selectedCategory : null]) }}"
                   class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-black hover:text-white border border-zinc-200 text-xs font-bold text-zinc-700 transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer">
                    <i class="ti ti-x text-xs"></i>
                    <span>Effacer la recherche</span>
                </a>
            </div>
        @endif

        <!-- 4-Column Product Catalog Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="product-card reveal-on-scroll group flex flex-col bg-white rounded-2xl p-3 sm:p-4 border border-zinc-100 shadow-[0_2px_10px_rgba(0,0,0,0.03)] hover:shadow-[0_12px_30px_rgba(0,0,0,0.08)] transition-all duration-300"
                     style="transition-delay: {{ ($loop->index % 4) * 80 }}ms;">
                    
                    <!-- Product Image Box linking to Product Subpage -->
                    <a href="{{ route('shop.product', $product['slug']) }}" class="relative aspect-square w-full bg-[#f8f9fa] rounded-xl overflow-hidden flex items-center justify-center p-6 mb-4 block">
                        
                        <!-- Watermark Typography Discount -->
                        <div class="absolute inset-0 flex flex-col justify-between p-3 select-none pointer-events-none z-0">
                            <span class="watermark-discount text-emerald-400/40 text-left font-black tracking-tighter">
                                {{ $product['discount'] }}
                            </span>
                            <span class="watermark-discount text-emerald-400/40 text-right font-black tracking-tighter">
                                off
                            </span>
                        </div>

                        <!-- Top Floating Pill Badge -->
                        <div class="absolute top-2.5 inset-x-0 flex justify-center z-20">
                            <span class="px-3 py-1 rounded-full text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider shadow-sm {{ $product['badge_color'] }}">
                                {{ $product['badge'] }}
                            </span>
                        </div>

                        <!-- Product Image Asset -->
                        <img src="{{ $product['image'] }}"
                             alt="{{ $product['name'] }}"
                             class="product-img relative z-10 w-full h-full object-contain max-h-[190px] group-hover:scale-108 transition-transform duration-500 ease-out" />
                    </a>

                    <!-- Product Details -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <!-- Rating Stars -->
                        <div class="flex items-center gap-1 text-amber-400 text-xs mb-1.5">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ti ti-star-filled text-[11px] {{ $i <= round($product['rating']) ? 'text-amber-400' : 'text-zinc-200' }}"></i>
                                @endfor
                            </div>
                            <span class="text-[10px] font-bold text-zinc-400">({{ $product['review_count'] }})</span>
                        </div>

                        <!-- Product Title with Link -->
                        <h3 class="text-sm sm:text-[15px] font-bold text-zinc-900 leading-snug mb-1 min-h-[40px] flex items-center justify-center">
                            <a href="{{ route('shop.product', $product['slug']) }}" class="hover:text-pink-600 transition-colors">
                                {{ $product['name'] }}
                            </a>
                        </h3>

                        <!-- Scent / Flavor Name Indicator -->
                        @if(!empty($product['flavors']))
                            <p class="flavor-label text-[11px] font-semibold text-zinc-400 mb-2">
                                {{ $product['flavors'][0]['name'] }}
                            </p>
                        @endif

                        <!-- Price in DH -->
                        <div class="flex flex-col items-center justify-center gap-0.5 mb-4">
                            <span class="text-lg sm:text-xl font-extrabold text-pink-600 tracking-tight">
                                {{ $product['price'] }} DH
                            </span>
                            <span class="text-xs font-semibold text-zinc-400 line-through">
                                {{ $product['original_price'] }} DH
                            </span>
                        </div>

                        <!-- Black Pill "Ajouter au panier" Button -->
                        <button class="btn-card-pill w-full mt-auto"
                                data-product-name="{{ $product['name'] }}"
                                data-product-price="{{ $product['price'] }}">
                            <i class="ti ti-shopping-bag-plus text-base"></i>
                            <span>Ajouter au panier</span>
                        </button>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 text-center text-zinc-400">
                    <i class="ti ti-package text-4xl mb-3 block"></i>
                    <p class="text-sm font-semibold">Aucun produit trouvé dans cette catégorie.</p>
                </div>
            @endforelse
        </div>

        <!-- Trust Features Strip -->
        <div class="reveal-on-scroll mt-16 sm:mt-24 p-8 bg-[#f8f9fa] rounded-3xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 border border-zinc-100">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center shrink-0">
                    <i class="ti ti-truck text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Livraison Offerte</h4>
                    <p class="text-[11px] text-zinc-500">Dès 500 DH partout au Maroc</p>
                </div>
            </div>
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="ti ti-leaf text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-zinc-900 uppercase tracking-wider">100% Vegan &amp; Clean</h4>
                    <p class="text-[11px] text-zinc-500">Sans parabènes ni phtalates</p>
                </div>
            </div>
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="ti ti-refresh text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Retours 14 Jours</h4>
                    <p class="text-[11px] text-zinc-500">Garantie satisfait ou remboursé</p>
                </div>
            </div>
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                    <i class="ti ti-shield-check text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Paiement Sécurisé</h4>
                    <p class="text-[11px] text-zinc-500">3D Secure, Apple Pay &amp; CB</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
