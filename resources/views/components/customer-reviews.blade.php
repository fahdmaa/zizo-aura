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
            
            <!-- Review 1 -->
            <div class="review-slide-card w-[300px] sm:w-[380px] lg:w-[420px] shrink-0 bg-zinc-100/90 rounded-3xl p-6 sm:p-8 border border-zinc-200 shadow-xs hover:-translate-y-2 hover:bg-zinc-100 hover:border-zinc-300 hover:shadow-lg hover:shadow-zinc-900/5 transition-all duration-300 ease-out flex flex-col justify-between">
                <div>
                    <!-- Top: Avatar, Name & Product Role -->
                    <div class="flex items-center gap-3.5 mb-5">
                        <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 ring-2 ring-pink-500/20 shadow-xs">
                            <img src="/images/reviews/sarah.jpg"
                                 alt="Sarah Laurent"
                                 class="w-full h-full object-cover object-center select-none" />
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-zinc-900 leading-tight flex items-center gap-1.5">
                                <span>Sarah Laurent</span>
                                <i class="ti ti-circle-check-filled text-pink-600 text-sm" title="Achat vérifié"></i>
                            </h3>
                            <p class="text-xs text-zinc-500 font-medium">Cliente vérifiée &bull; Bare Vanilla Duo</p>
                        </div>
                    </div>

                    <!-- Stars -->
                    <div class="flex items-center text-amber-400 text-xs mb-3">
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                    </div>

                    <!-- Testimonial Quote -->
                    <p class="text-xs sm:text-sm text-zinc-600 font-normal leading-relaxed">
                        &laquo;&nbsp;Commande reçue en 48h chrono ! Le pack Bare Vanilla est absolument divin et 100% authentique. Les petits échantillons offerts dans le colis sont une délicate attention.&nbsp;&raquo;
                    </p>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="review-slide-card w-[300px] sm:w-[380px] lg:w-[420px] shrink-0 bg-zinc-100/90 rounded-3xl p-6 sm:p-8 border border-zinc-200 shadow-xs hover:-translate-y-2 hover:bg-zinc-100 hover:border-zinc-300 hover:shadow-lg hover:shadow-zinc-900/5 transition-all duration-300 ease-out flex flex-col justify-between">
                <div>
                    <!-- Top: Avatar, Name & Product Role -->
                    <div class="flex items-center gap-3.5 mb-5">
                        <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 ring-2 ring-amber-500/20 shadow-xs">
                            <img src="/images/reviews/yasmine.jpg"
                                 alt="Yasmine Benali"
                                 class="w-full h-full object-cover object-center select-none" />
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-zinc-900 leading-tight flex items-center gap-1.5">
                                <span>Yasmine Benali</span>
                                <i class="ti ti-circle-check-filled text-pink-600 text-sm" title="Achat vérifié"></i>
                            </h3>
                            <p class="text-xs text-zinc-500 font-medium">Cliente vérifiée &bull; Rituals Sakura Set</p>
                        </div>
                    </div>

                    <!-- Stars -->
                    <div class="flex items-center text-amber-400 text-xs mb-3">
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                    </div>

                    <!-- Testimonial Quote -->
                    <p class="text-xs sm:text-sm text-zinc-600 font-normal leading-relaxed">
                        &laquo;&nbsp;L'emballage origami Rituals Sakura est splendide, prêt à être offert ! La mousse de douche est tellement onctueuse et le parfum de fleur de cerisier tient toute la journée.&nbsp;&raquo;
                    </p>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="review-slide-card w-[300px] sm:w-[380px] lg:w-[420px] shrink-0 bg-zinc-100/90 rounded-3xl p-6 sm:p-8 border border-zinc-200 shadow-xs hover:-translate-y-2 hover:bg-zinc-100 hover:border-zinc-300 hover:shadow-lg hover:shadow-zinc-900/5 transition-all duration-300 ease-out flex flex-col justify-between">
                <div>
                    <!-- Top: Avatar, Name & Product Role -->
                    <div class="flex items-center gap-3.5 mb-5">
                        <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 ring-2 ring-rose-500/20 shadow-xs">
                            <img src="/images/reviews/camille.jpg"
                                 alt="Camille Moreau"
                                 class="w-full h-full object-cover object-center select-none" />
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-zinc-900 leading-tight flex items-center gap-1.5">
                                <span>Camille Moreau</span>
                                <i class="ti ti-circle-check-filled text-pink-600 text-sm" title="Achat vérifié"></i>
                            </h3>
                            <p class="text-xs text-zinc-500 font-medium">Cliente vérifiée &bull; Bum Bum Jet Set</p>
                        </div>
                    </div>

                    <!-- Stars -->
                    <div class="flex items-center text-amber-400 text-xs mb-3">
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                    </div>

                    <!-- Testimonial Quote -->
                    <p class="text-xs sm:text-sm text-zinc-600 font-normal leading-relaxed">
                        &laquo;&nbsp;Le Bum Bum Jet Set est un indispensable de l'été ! L'odeur de pistache et caramel salé est complètement addictive. Prix super avantageux avec la réduction.&nbsp;&raquo;
                    </p>
                </div>
            </div>

            <!-- Review 4 -->
            <div class="review-slide-card w-[300px] sm:w-[380px] lg:w-[420px] shrink-0 bg-zinc-100/90 rounded-3xl p-6 sm:p-8 border border-zinc-200 shadow-xs hover:-translate-y-2 hover:bg-zinc-100 hover:border-zinc-300 hover:shadow-lg hover:shadow-zinc-900/5 transition-all duration-300 ease-out flex flex-col justify-between">
                <div>
                    <!-- Top: Avatar, Name & Product Role -->
                    <div class="flex items-center gap-3.5 mb-5">
                        <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 ring-2 ring-purple-500/20 shadow-xs">
                            <img src="/images/reviews/lea.jpg"
                                 alt="Léa Dubois"
                                 class="w-full h-full object-cover object-center select-none" />
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-zinc-900 leading-tight flex items-center gap-1.5">
                                <span>Léa Dubois</span>
                                <i class="ti ti-circle-check-filled text-pink-600 text-sm" title="Achat vérifié"></i>
                            </h3>
                            <p class="text-xs text-zinc-500 font-medium">Cliente vérifiée &bull; VS Bombshell Prestige</p>
                        </div>
                    </div>

                    <!-- Stars -->
                    <div class="flex items-center text-amber-400 text-xs mb-3">
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                    </div>

                    <!-- Testimonial Quote -->
                    <p class="text-xs sm:text-sm text-zinc-600 font-normal leading-relaxed">
                        &laquo;&nbsp;Le flacon Bombshell en cristal avec son nœud satiné est une merveille. La crème pour le corps sublime la peau et fait tenir le parfum toute la soirée.&nbsp;&raquo;
                    </p>
                </div>
            </div>

            <!-- Review 5 -->
            <div class="review-slide-card w-[300px] sm:w-[380px] lg:w-[420px] shrink-0 bg-zinc-100/90 rounded-3xl p-6 sm:p-8 border border-zinc-200 shadow-xs hover:-translate-y-2 hover:bg-zinc-100 hover:border-zinc-300 hover:shadow-lg hover:shadow-zinc-900/5 transition-all duration-300 ease-out flex flex-col justify-between">
                <div>
                    <!-- Top: Avatar, Name & Product Role -->
                    <div class="flex items-center gap-3.5 mb-5">
                        <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 ring-2 ring-emerald-500/20 shadow-xs">
                            <img src="/images/reviews/nadia.jpg"
                                 alt="Nadia Fourati"
                                 class="w-full h-full object-cover object-center select-none" />
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-zinc-900 leading-tight flex items-center gap-1.5">
                                <span>Nadia Fourati</span>
                                <i class="ti ti-circle-check-filled text-pink-600 text-sm" title="Achat vérifié"></i>
                            </h3>
                            <p class="text-xs text-zinc-500 font-medium">Cliente vérifiée &bull; Rituals Ayurveda</p>
                        </div>
                    </div>

                    <!-- Stars -->
                    <div class="flex items-center text-amber-400 text-xs mb-3">
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                    </div>

                    <!-- Testimonial Quote -->
                    <p class="text-xs sm:text-sm text-zinc-600 font-normal leading-relaxed">
                        &laquo;&nbsp;Rituals Ayurveda est mon rituel réconfortant préféré. L'accord rose indienne et amande douce laisse la peau nourrie et satinée. Colis très bien sécurisé.&nbsp;&raquo;
                    </p>
                </div>
            </div>

            <!-- Review 6 -->
            <div class="review-slide-card w-[300px] sm:w-[380px] lg:w-[420px] shrink-0 bg-zinc-100/90 rounded-3xl p-6 sm:p-8 border border-zinc-200 shadow-xs hover:-translate-y-2 hover:bg-zinc-100 hover:border-zinc-300 hover:shadow-lg hover:shadow-zinc-900/5 transition-all duration-300 ease-out flex flex-col justify-between">
                <div>
                    <!-- Top: Avatar, Name & Product Role -->
                    <div class="flex items-center gap-3.5 mb-5">
                        <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 ring-2 ring-teal-500/20 shadow-xs">
                            <img src="/images/reviews/emma.jpg"
                                 alt="Emma Vidal"
                                 class="w-full h-full object-cover object-center select-none" />
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-zinc-900 leading-tight flex items-center gap-1.5">
                                <span>Emma Vidal</span>
                                <i class="ti ti-circle-check-filled text-pink-600 text-sm" title="Achat vérifié"></i>
                            </h3>
                            <p class="text-xs text-zinc-500 font-medium">Cliente vérifiée &bull; Beija Flor Jet Set</p>
                        </div>
                    </div>

                    <!-- Stars -->
                    <div class="flex items-center text-amber-400 text-xs mb-3">
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                    </div>

                    <!-- Testimonial Quote -->
                    <p class="text-xs sm:text-sm text-zinc-600 font-normal leading-relaxed">
                        &laquo;&nbsp;Le Beija Flor Jet Set avec la brume 68 sent divinement bon les fleurs fraîches et les vacances. Ma peau est visiblement plus rebondie avec la crème.&nbsp;&raquo;
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>
