<!-- 4-Column Product Catalog Grid -->
<div id="shop-product-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    @forelse($products as $product)
        <div class="product-card reveal-on-scroll group flex flex-col bg-white rounded-2xl p-3.5 sm:p-4 border border-zinc-100 shadow-[0_2px_10px_rgba(0,0,0,0.03)] hover:shadow-[0_12px_30px_rgba(0,0,0,0.08)] transition-all duration-300"
             style="transition-delay: {{ ($loop->index % 4) * 80 }}ms;">
            
            <!-- Product Image Box linking to Product Subpage -->
            <a href="{{ route('shop.product', $product['slug']) }}" class="relative aspect-square w-full bg-[#f8f9fa] rounded-xl overflow-hidden flex items-center justify-center p-6 mb-4 block">
                
                <!-- Watermark Typography Discount -->
                @if(!empty($product['discount']))
                    <div class="absolute inset-0 flex flex-col justify-between p-3 select-none pointer-events-none z-0">
                        <span class="watermark-discount text-zinc-200/70 text-left font-black tracking-tighter">
                            {{ $product['discount'] }}
                        </span>
                        <span class="watermark-discount text-zinc-200/70 text-right font-black tracking-tighter">
                            remise
                        </span>
                    </div>
                @endif

                <!-- Top Floating Pill Badge -->
                @if(!empty($product['badge']))
                    <div class="absolute top-2.5 inset-x-0 flex justify-center z-20">
                        <span class="px-3 py-1 rounded-full text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider shadow-sm {{ $product['badge_color'] }}">
                            {{ $product['badge'] }}
                        </span>
                    </div>
                @endif

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
                            @if($i <= round($product['rating']))
                                <i class="uis uis-star text-[11px]"></i>
                            @else
                                <i class="uil uil-star text-[11px] text-zinc-200"></i>
                            @endif
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
                    @if(!empty($product['discount']) && !empty($product['original_price']) && $product['original_price'] !== $product['price'])
                        <span class="text-xs font-semibold text-zinc-400 line-through">
                            {{ $product['original_price'] }} DH
                        </span>
                    @endif
                </div>

                @if($product['is_active'] ?? true)
                    <!-- Black Pill "Ajouter au panier" Button -->
                    <button class="btn-card-pill w-full mt-auto cursor-pointer"
                            data-add-to-cart
                            data-product-name="{{ $product['name'] }}"
                            data-product-price="{{ $product['price'] }}"
                            data-product-image="{{ $product['image'] }}"
                            data-product-slug="{{ $product['slug'] }}">
                        <i class="uil uil-shopping-bag text-base"></i>
                        <span>Ajouter au panier</span>
                    </button>
                @else
                    <!-- Black Pill "Précommander" Button -->
                    <button class="btn-card-pill w-full mt-auto !bg-zinc-900 hover:!bg-black text-white cursor-pointer"
                            data-preorder-product
                            data-product-id="{{ $product['id'] ?? '' }}"
                            data-product-name="{{ $product['name'] }}"
                            data-product-price="{{ $product['price'] }}"
                            data-product-image="{{ $product['image'] }}"
                            data-product-slug="{{ $product['slug'] }}">
                        <i class="uil uil-clock text-base text-amber-400"></i>
                        <span>Précommander</span>
                    </button>
                @endif
            </div>

        </div>
    @empty
        <div class="col-span-full py-16 text-center text-zinc-400">
            <i class="uil uil-box text-4xl mb-3 block"></i>
            <p class="text-sm font-semibold">Aucun produit trouvé dans cette catégorie.</p>
        </div>
    @endforelse
</div>
