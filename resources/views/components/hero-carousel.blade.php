<section id="hero-carousel-section" class="w-full relative overflow-hidden bg-[#0c0907] text-white border-b border-zinc-900/80 select-none isolate" data-hero-brand="sol-de-janeiro">
    
    <!-- =========================================================================
         1. DYNAMIC AMBIENT AURA BACKGROUND
         ========================================================================= -->
    <div class="hero-aura-container pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="hero-aura-deep absolute inset-0"></div>
        <div class="hero-aura-sweep hero-aura-sweep-a absolute"></div>
        <div class="hero-aura-sweep hero-aura-sweep-b absolute"></div>
        <div class="hero-aura-core absolute"></div>
        <div class="hero-aura-vignette absolute inset-0"></div>
        <div class="hero-aura-grain absolute inset-0"></div>
    </div>

    <!-- =========================================================================
         2. CAROUSEL STAGE & 3D VIEWPORT (No overlay CTA buttons, pure image banners)
         ========================================================================= -->
    <div class="relative w-full max-w-[1560px] mx-auto pt-4 sm:pt-6 lg:pt-8 pb-3 sm:pb-4 px-2 sm:px-6 lg:px-12 flex flex-col items-center">
        
        <!-- Main Carousel Stage -->
        <div id="heroCarouselStage" class="relative w-full flex items-center justify-center overflow-visible my-2 sm:my-4" aria-roledescription="carousel" aria-label="Curated Beauty Carousel">
            
            <!-- Left Manual Switch Arrow -->
            <button type="button"
                    id="heroPrevBtn"
                    aria-label="Diapositive précédente"
                    class="hero-nav-arrow hero-nav-arrow-prev group">
                <i class="ti ti-chevron-left text-xl sm:text-2xl group-hover:-translate-x-0.5 transition-transform"></i>
            </button>

            <!-- 3D Carousel Viewport & Track -->
            <div class="hero-carousel-viewport w-full max-w-[1140px] flex items-center justify-center">
                <div id="heroCarouselTrack" class="hero-carousel-track w-full flex items-center justify-center">
                    
                    <!-- Slide 1: Sol de Janeiro -->
                    <div class="hero-slide is-active" data-index="0" data-brand="sol-de-janeiro" role="group" aria-roledescription="slide" aria-label="1 of 4: Sol de Janeiro">
                        <div class="hero-slide-card">
                            <div class="hero-slide-media">
                                <img src="/images/hero-carousel/sol-de-janeiro.png"
                                     alt="Sol de Janeiro Collection - zizo aura"
                                     class="hero-slide-img"
                                     loading="eager"
                                     draggable="false" />
                                <div class="hero-slide-glow"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2: Rituals -->
                    <div class="hero-slide is-next" data-index="1" data-brand="rituals" role="group" aria-roledescription="slide" aria-label="2 of 4: Rituals">
                        <div class="hero-slide-card">
                            <div class="hero-slide-media">
                                <img src="/images/hero-carousel/rituals.png"
                                     alt="Rituals Collection - zizo aura"
                                     class="hero-slide-img"
                                     loading="eager"
                                     draggable="false" />
                                <div class="hero-slide-glow"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3: Victoria's Secret -->
                    <div class="hero-slide is-hidden-right" data-index="2" data-brand="victoria-secret" role="group" aria-roledescription="slide" aria-label="3 of 4: Victoria's Secret">
                        <div class="hero-slide-card">
                            <div class="hero-slide-media">
                                <img src="/images/hero-carousel/victoria-secret.png"
                                     alt="Victoria's Secret Collection - zizo aura"
                                     class="hero-slide-img"
                                     loading="lazy"
                                     draggable="false" />
                                <div class="hero-slide-glow"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4: The Ordinary -->
                    <div class="hero-slide is-prev" data-index="3" data-brand="ordinary" role="group" aria-roledescription="slide" aria-label="4 of 4: The Ordinary">
                        <div class="hero-slide-card">
                            <div class="hero-slide-media">
                                <img src="/images/hero-carousel/ordinary.png"
                                     alt="The Ordinary Clinical Skincare - zizo aura"
                                     class="hero-slide-img"
                                     loading="lazy"
                                     draggable="false" />
                                <div class="hero-slide-glow"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Manual Switch Arrow -->
            <button type="button"
                    id="heroNextBtn"
                    aria-label="Diapositive suivante"
                    class="hero-nav-arrow hero-nav-arrow-next group">
                <i class="ti ti-chevron-right text-xl sm:text-2xl group-hover:translate-x-0.5 transition-transform"></i>
            </button>

        </div>

        <!-- =========================================================================
             3. BOTTOM CONTROLS & MANUAL BRAND TABS (with progress indicators)
             ========================================================================= -->
        <div class="hero-carousel-rail w-full max-w-[1140px] mt-3 sm:mt-5 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 py-2 sm:py-3 px-3 sm:px-5 rounded-2xl bg-black/40 backdrop-blur-xl border border-white/10 shadow-lg">
            
            <!-- Left: Counter Display -->
            <div class="flex items-center gap-2 font-mono text-xs sm:text-sm text-zinc-400 font-semibold shrink-0">
                <span id="heroCounterCurrent" class="text-base sm:text-lg font-black text-pink-400">01</span>
                <span class="text-zinc-600">/</span>
                <span class="text-zinc-400">04</span>
            </div>

            <!-- Center: 4 Brand Selection Tabs -->
            <div class="flex items-center justify-center gap-1.5 sm:gap-3 flex-wrap w-full sm:w-auto" role="tablist" aria-label="Sélection rapide de marque">
                
                <button type="button"
                        class="hero-brand-tab is-active group"
                        data-slide-target="0"
                        role="tab"
                        aria-selected="true"
                        aria-label="Voir Sol de Janeiro">
                    <div class="flex items-center gap-1.5">
                        <span class="hero-tab-num font-mono text-[10px] text-zinc-500 font-semibold group-hover:text-white transition-colors">01</span>
                        <span class="hero-tab-name text-xs sm:text-sm font-bold text-zinc-300 group-hover:text-white transition-colors">Sol de Janeiro</span>
                    </div>
                    <div class="hero-tab-track">
                        <div class="hero-tab-progress"></div>
                    </div>
                </button>

                <button type="button"
                        class="hero-brand-tab group"
                        data-slide-target="1"
                        role="tab"
                        aria-selected="false"
                        aria-label="Voir Rituals">
                    <div class="flex items-center gap-1.5">
                        <span class="hero-tab-num font-mono text-[10px] text-zinc-500 font-semibold group-hover:text-white transition-colors">02</span>
                        <span class="hero-tab-name text-xs sm:text-sm font-bold text-zinc-300 group-hover:text-white transition-colors">Rituals</span>
                    </div>
                    <div class="hero-tab-track">
                        <div class="hero-tab-progress"></div>
                    </div>
                </button>

                <button type="button"
                        class="hero-brand-tab group"
                        data-slide-target="2"
                        role="tab"
                        aria-selected="false"
                        aria-label="Voir Victoria's Secret">
                    <div class="flex items-center gap-1.5">
                        <span class="hero-tab-num font-mono text-[10px] text-zinc-500 font-semibold group-hover:text-white transition-colors">03</span>
                        <span class="hero-tab-name text-xs sm:text-sm font-bold text-zinc-300 group-hover:text-white transition-colors">Victoria's Secret</span>
                    </div>
                    <div class="hero-tab-track">
                        <div class="hero-tab-progress"></div>
                    </div>
                </button>

                <button type="button"
                        class="hero-brand-tab group"
                        data-slide-target="3"
                        role="tab"
                        aria-selected="false"
                        aria-label="Voir The Ordinary">
                    <div class="flex items-center gap-1.5">
                        <span class="hero-tab-num font-mono text-[10px] text-zinc-500 font-semibold group-hover:text-white transition-colors">04</span>
                        <span class="hero-tab-name text-xs sm:text-sm font-bold text-zinc-300 group-hover:text-white transition-colors">The Ordinary</span>
                    </div>
                    <div class="hero-tab-track">
                        <div class="hero-tab-progress"></div>
                    </div>
                </button>

            </div>

            <!-- Right: Play/Pause Autoplay Toggle Button -->
            <div class="flex items-center justify-end shrink-0">
                <button type="button"
                        id="heroAutoplayToggle"
                        class="hero-autoplay-btn group"
                        aria-label="Mettre en pause ou reprendre le défilement automatique"
                        title="Pause / Reprendre">
                    <span class="hero-autoplay-icon-pause flex items-center justify-center">
                        <i class="ti ti-player-pause text-xs"></i>
                    </span>
                    <span class="hero-autoplay-icon-play hidden items-center justify-center text-pink-400">
                        <i class="ti ti-player-play text-xs"></i>
                    </span>
                    <span class="text-[11px] font-mono tracking-wider text-zinc-400 uppercase font-semibold group-hover:text-white transition-colors">Auto</span>
                </button>
            </div>

        </div>

    </div>
</section>
