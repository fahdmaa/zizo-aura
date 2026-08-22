@props(['products' => []])

<section id="shop" class="w-full bg-white py-8 sm:py-16">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Promo Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 sm:mb-12">
            <div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black tracking-tight mb-2">
                    Jusqu'à <span class="text-pink-500">-50% sur toute la boutique</span>
                </h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-medium">
                    Notre plus grande Vente d'Été. Utilisez le code <span class="bg-pink-100 text-pink-600 font-bold px-2 py-0.5 rounded text-xs">RIO50</span> lors de votre commande.
                </p>
            </div>

            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-zinc-900 hover:text-pink-600 transition-colors uppercase tracking-wider">
                <span>Tout voir</span>
                <i class="ti ti-chevron-right text-base"></i>
            </a>
        </div>

        <!-- 4-Column Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="product-card group flex flex-col bg-white rounded-2xl p-3 sm:p-4 border border-zinc-100 shadow-[0_2px_10px_rgba(0,0,0,0.03)] hover:shadow-[0_12px_30px_rgba(0,0,0,0.08)] transition-all duration-300">
                    
                    <!-- Product Image Container with Huge Watermark & Floating Badge (Links to Product Detail) -->
                    <a href="{{ route('shop.product', $product['slug']) }}" class="relative aspect-square w-full bg-[#f8f9fa] rounded-xl overflow-hidden flex items-center justify-center p-6 mb-4 block">
                        
                        <!-- Huge Mint / Teal Watermark Typography Discount -->
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

                    <!-- Product Info Details -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <!-- Product Title linking to detail subpage -->
                        <h3 class="text-sm sm:text-[15px] font-bold text-zinc-900 leading-snug mb-2 min-h-[40px] flex items-center justify-center">
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

                        <!-- Price: Magenta Discount Price + Strikethrough Original in Euros -->
                        <div class="flex flex-col items-center justify-center gap-0.5 mb-4">
                            <span class="text-lg sm:text-xl font-extrabold text-pink-600 tracking-tight">
                                {{ $product['price'] }} DH
                            </span>
                            <span class="text-xs font-semibold text-zinc-400 line-through">
                                {{ $product['original_price'] }} DH
                            </span>
                        </div>

                        <!-- Black Pill "Ajouter au panier" CTA Button -->
                        <button class="btn-card-pill w-full mt-auto"
                                data-product-name="{{ $product['name'] }}"
                                data-product-price="{{ $product['price'] }}">
                            <i class="ti ti-shopping-bag-plus text-base"></i>
                            <span>Ajouter au panier</span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
