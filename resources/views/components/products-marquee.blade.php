@props(['products' => []])

<section id="offers" class="reveal-on-scroll w-full bg-white py-12 sm:py-16 overflow-hidden border-b border-zinc-100">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black tracking-tight">
                    Nos 8 Meilleures Offres &amp; Réductions
                </h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-medium mt-1">
                    Jusqu'à -35% de réduction immédiate sur nos duos et coffrets les plus convoités.
                </p>
            </div>

            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-extrabold text-zinc-900 hover:text-pink-600 transition-colors uppercase tracking-wider whitespace-nowrap">
                <span>Voir toute la boutique</span>
                <i class="ti ti-arrow-right text-base"></i>
            </a>
        </div>
    </div>

    <!-- Infinite Smooth Horizontal Moving Line Container -->
    <div class="relative w-full overflow-hidden group/track select-none">
        
        <!-- Subtle Side Fade Gradients for Luxury Look -->
        <div class="absolute left-0 top-0 bottom-0 w-16 sm:w-28 bg-gradient-to-r from-white to-transparent z-20 pointer-events-none"></div>
        <div class="absolute right-0 top-0 bottom-0 w-16 sm:w-28 bg-gradient-to-l from-white to-transparent z-20 pointer-events-none"></div>

        <!-- Marquee Track (Double repeat for seamless infinite loop) -->
        <div class="animate-marquee-infinite flex items-stretch gap-6 py-4 px-4">
            
            @for($repeat = 0; $repeat < 2; $repeat++)
                @foreach($products as $product)
                    <div class="product-card w-[260px] sm:w-[300px] shrink-0 flex flex-col bg-white rounded-2xl p-3 sm:p-4 border border-zinc-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] hover:shadow-[0_14px_32px_rgba(0,0,0,0.1)] transition-all duration-300">
                        
                        <!-- Image Container with Watermark Discount -->
                        <a href="{{ route('shop.product', $product['slug']) }}" class="relative aspect-square w-full bg-[#f8f9fa] rounded-xl overflow-hidden flex items-center justify-center p-5 mb-3.5 block">
                            
                            <!-- Watermark Discount -->
                            <div class="absolute inset-0 flex flex-col justify-between p-3 select-none pointer-events-none z-0">
                                <span class="watermark-discount text-emerald-400/40 text-left font-black tracking-tighter">
                                    {{ $product['discount'] }}
                                </span>
                                <span class="watermark-discount text-emerald-400/40 text-right font-black tracking-tighter">
                                    off
                                </span>
                            </div>

                            <!-- Floating Badge Pill -->
                            <div class="absolute top-2.5 inset-x-0 flex justify-center z-20">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider shadow-sm {{ $product['badge_color'] }}">
                                    {{ $product['badge'] }}
                                </span>
                            </div>

                            <!-- Product Image -->
                            <img src="{{ $product['image'] }}"
                                 alt="{{ $product['name'] }}"
                                 class="product-img relative z-10 w-full h-full object-contain max-h-[175px] group-hover:scale-108 transition-transform duration-500 ease-out" />
                        </a>

                        <!-- Product Info -->
                        <div class="flex flex-col items-center text-center flex-1">
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-pink-600 mb-1">
                                {{ $product['brand'] ?? 'zizo aura' }}
                            </span>

                            <h3 class="text-xs sm:text-[13px] font-bold text-zinc-900 leading-snug mb-1 min-h-[36px] line-clamp-2 flex items-center justify-center">
                                <a href="{{ route('shop.product', $product['slug']) }}" class="hover:text-pink-600 transition-colors">
                                    {{ $product['name'] }}
                                </a>
                            </h3>

                            <!-- Price in DH -->
                            <div class="flex items-center justify-center gap-1.5 mb-3.5">
                                <span class="text-base sm:text-lg font-black text-pink-600 tracking-tight">
                                    {{ $product['price'] }} DH
                                </span>
                                <span class="text-xs font-semibold text-zinc-400 line-through">
                                    {{ $product['original_price'] }} DH
                                </span>
                            </div>

                            <!-- Quick Add to Cart Button -->
                            <button class="btn-card-pill w-full mt-auto py-2.5 text-xs font-bold"
                                    data-product-name="{{ $product['name'] }}"
                                    data-product-price="{{ $product['price'] }}"
                                    data-product-image="{{ $product['image'] }}"
                                    data-product-slug="{{ $product['slug'] }}">
                                <i class="ti ti-shopping-bag-plus text-sm"></i>
                                <span>Ajouter au panier</span>
                            </button>
                        </div>

                    </div>
                @endforeach
            @endfor

        </div>
    </div>
</section>
