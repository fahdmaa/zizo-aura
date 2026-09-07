@props(['reviews' => null])

@php
    $reviewsList = $reviews ?? \App\Models\Review::visible()->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
@endphp

@if($reviewsList && count($reviewsList) > 0)
<section class="reveal-on-scroll w-full bg-[#fafafa]/50 py-16 sm:py-24 border-b border-zinc-100/80 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header: Eyebrow, Title & Subtitle on Left, Minimalist Circle Arrows on Right -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-12 sm:mb-16">
            <div class="max-w-2xl">
                <p class="text-[11px] sm:text-xs font-semibold uppercase tracking-[0.25em] text-zinc-400 mb-2 select-none">
                    TÉMOIGNAGES
                </p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-950 tracking-tight leading-tight">
                    Ce que disent nos clientes
                </h2>
                <p class="text-xs sm:text-sm text-zinc-500 font-normal leading-relaxed max-w-lg mt-2">
                    Découvrez ce que nos clientes pensent de leur expérience avec nos soins et coffrets officiels.
                </p>
            </div>

            <!-- Carousel Navigation Arrows (White Outline Prev + Black Next) -->
            <div class="flex items-center gap-3 shrink-0">
                <button id="review-prev"
                        aria-label="Avis précédent"
                        class="w-12 h-12 rounded-full bg-white hover:bg-zinc-50 border border-zinc-200/90 hover:border-zinc-300 text-zinc-700 hover:text-black flex items-center justify-center text-base shadow-xs hover:scale-105 active:scale-95 transition-all duration-200 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </button>
                <button id="review-next"
                        aria-label="Avis suivant"
                        class="w-12 h-12 rounded-full bg-zinc-950 hover:bg-zinc-800 text-white flex items-center justify-center text-base shadow-md hover:scale-105 active:scale-95 transition-all duration-200 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Reviews Horizontal Slider Track -->
        <div id="reviews-slider" class="flex gap-6 overflow-x-auto scroll-smooth no-scrollbar pt-2 pb-8 -mx-4 px-4 sm:mx-0 sm:px-0">
            @foreach($reviewsList as $index => $review)
                @php
                    $avatarSrc = $review->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($review->author_name) . '&background=ff1b7a&color=fff&size=128';
                    $rating = (int) ($review->rating ?? 5);
                @endphp
                <div class="review-slide-card w-[85vw] max-w-[340px] sm:w-[380px] lg:w-[410px] shrink-0 bg-white rounded-3xl p-7 sm:p-8 border border-zinc-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between"
                     data-index="{{ $index }}">
                    <div>
                        <!-- Top: Avatar, Name & Product Role -->
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-14 h-14 rounded-full overflow-hidden shrink-0 border border-zinc-100 shadow-xs bg-zinc-50">
                                <img src="{{ $avatarSrc }}"
                                     alt="{{ $review->author_name }}"
                                     class="w-full h-full object-cover object-center select-none"
                                     loading="lazy" />
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base font-bold text-zinc-900 leading-tight flex items-center gap-1.5 truncate">
                                    <span class="truncate">{{ $review->author_name }}</span>
                                    <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-pink-600 text-white shrink-0 shadow-2xs" title="{{ $review->badge ?: 'Cliente vérifiée' }}">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </h3>
                                @if($review->author_role)
                                    <p class="text-xs text-zinc-400 font-medium mt-0.5 truncate">{{ $review->author_role }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- 5 Gold Stars -->
                        <div class="flex items-center gap-1 text-[#f59e0b] text-sm mt-5 mb-4 select-none">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rating)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-zinc-200 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>

                        <!-- Testimonial Quote -->
                        <p class="text-xs sm:text-sm text-zinc-600 font-normal leading-relaxed">
                            &laquo;&nbsp;{{ $review->comment }}&nbsp;&raquo;
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bottom Footer: Tagline on Left, Counter & Progress Bar on Right -->
        <div class="flex items-center justify-between pt-6 border-t border-zinc-100/80 mt-4 text-zinc-400">
            <div class="flex items-center gap-3">
                <span class="w-8 sm:w-12 h-[1px] bg-zinc-200 inline-block"></span>
                <span class="text-[10px] sm:text-[11px] font-semibold uppercase tracking-[0.25em] text-zinc-400 select-none">
                    DES CLIENTES HEUREUSES, TOUJOURS
                </span>
            </div>

            <div class="flex items-center gap-3">
                <span id="review-counter" class="text-xs font-semibold text-zinc-500 tracking-wider select-none">
                    01 / {{ sprintf('%02d', count($reviewsList)) }}
                </span>
                <div class="w-16 sm:w-24 h-[2px] bg-zinc-100 rounded-full overflow-hidden relative">
                    <div id="review-progress-bar" class="h-full bg-zinc-900 rounded-full transition-all duration-300" style="width: {{ count($reviewsList) > 0 ? (100 / count($reviewsList)) : 100 }}%;"></div>
                </div>
            </div>
        </div>

    </div>
</section>
@endif
