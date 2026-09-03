@props(['reviews' => null])

@php
    $reviewsList = $reviews ?? \App\Models\Review::visible()->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
@endphp

@if($reviewsList && count($reviewsList) > 0)
<section class="reveal-on-scroll w-full bg-white py-16 sm:py-24 border-b border-zinc-100 overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header: Title & Subtitle on Left, Navigation Arrows on Right (Matching Theme) -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-12 sm:mb-16">
            <div class="max-w-2xl">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-900 tracking-tight leading-tight mb-3">
                    Ce que disent nos clientes
                </h2>
                <p class="text-sm sm:text-base text-zinc-500 font-normal leading-relaxed">
                    Découvrez ce que nos clientes pensent de leur expérience avec nos soins et coffrets officiels.
                </p>
            </div>

            <!-- Carousel Navigation Arrows (Right Arrow Highlighted in Pink) -->
            <div class="flex items-center gap-3 shrink-0">
                <button id="review-prev"
                        aria-label="Avis précédent"
                        class="btn-circle-action w-11 h-11 rounded-full bg-white hover:bg-pink-50 border border-zinc-200 hover:border-pink-300 text-zinc-800 hover:text-pink-600 flex items-center justify-center text-base shadow-2xs transition-all">
                    <i class="ti ti-arrow-left"></i>
                </button>
                <button id="review-next"
                        aria-label="Avis suivant"
                        class="btn-circle-action w-11 h-11 rounded-full bg-zinc-900 hover:bg-pink-600 border border-zinc-900 hover:border-pink-600 text-white flex items-center justify-center text-base shadow-sm transition-all">
                    <i class="ti ti-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Reviews Horizontal Slider Track -->
        <div id="reviews-slider" class="flex gap-6 overflow-x-auto scroll-smooth no-scrollbar pt-3 pb-8 -mx-4 px-4 sm:mx-0 sm:px-0">
            @foreach($reviewsList as $review)
                @php
                    $ringClass = match($review->ring_color ?? 'pink') {
                        'amber' => 'ring-amber-500/20',
                        'rose' => 'ring-rose-500/20',
                        'purple' => 'ring-purple-500/20',
                        'emerald' => 'ring-emerald-500/20',
                        'teal' => 'ring-teal-500/20',
                        'blue' => 'ring-blue-500/20',
                        'indigo' => 'ring-indigo-500/20',
                        default => 'ring-pink-500/20',
                    };
                    $avatarSrc = $review->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($review->author_name) . '&background=ff1b7a&color=fff&size=128';
                    $rating = (int) ($review->rating ?? 5);
                @endphp
                <div class="review-slide-card w-[82vw] max-w-[340px] sm:w-[380px] lg:w-[420px] shrink-0 bg-zinc-100/90 rounded-3xl p-6 sm:p-8 border border-zinc-200 shadow-xs hover:-translate-y-2 hover:bg-zinc-100 hover:border-zinc-300 hover:shadow-lg hover:shadow-zinc-900/5 transition-all duration-300 ease-out flex flex-col justify-between">
                    <div>
                        <!-- Top: Avatar, Name & Product Role -->
                        <div class="flex items-center gap-3.5 mb-5">
                            <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 ring-2 {{ $ringClass }} shadow-xs">
                                <img src="{{ $avatarSrc }}"
                                     alt="{{ $review->author_name }}"
                                     class="w-full h-full object-cover object-center select-none" />
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-extrabold text-zinc-900 leading-tight flex items-center gap-1.5">
                                    <span>{{ $review->author_name }}</span>
                                    <i class="ti ti-circle-check-filled text-pink-600 text-sm" title="{{ $review->badge ?: 'Achat vérifié' }}"></i>
                                </h3>
                                @if($review->author_role)
                                    <p class="text-xs text-zinc-500 font-medium">{{ $review->author_role }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Stars -->
                        <div class="flex items-center text-amber-400 text-xs mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rating)
                                    <i class="ti ti-star-filled"></i>
                                @else
                                    <i class="ti ti-star text-zinc-300"></i>
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

    </div>
</section>
@endif
