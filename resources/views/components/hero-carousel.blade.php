<section id="hero-carousel-section" class="w-full relative overflow-hidden bg-white select-none border-b border-zinc-100" aria-label="Bannière principale zizo aura">
    
    <!-- Full-Width Hero Slider Viewport -->
    <div id="heroCarouselStage" class="relative w-full overflow-hidden aspect-[1717/916] min-h-[260px] sm:min-h-[380px] md:min-h-[460px] lg:min-h-[540px] xl:min-h-[620px] bg-zinc-100">
        
        <!-- Sliding Horizontal Track -->
        <div id="heroCarouselTrack" class="flex w-full h-full will-change-transform transition-transform duration-700 ease-[cubic-bezier(0.25,1,0.5,1)]">
            
            <!-- Slide 1: Sol de Janeiro -->
            <div class="hero-slide w-full h-full shrink-0 relative" data-index="0" data-brand="sol-de-janeiro" role="group" aria-roledescription="slide" aria-label="1 sur 4 : Sol de Janeiro">
                <img src="/images/hero-carousel/sol-de-janeiro.png"
                     alt="Sol de Janeiro Collection - zizo aura"
                     class="w-full h-full object-cover object-center select-none pointer-events-none"
                     loading="eager"
                     draggable="false" />
            </div>

            <!-- Slide 2: Rituals -->
            <div class="hero-slide w-full h-full shrink-0 relative" data-index="1" data-brand="rituals" role="group" aria-roledescription="slide" aria-label="2 sur 4 : Rituals">
                <img src="/images/hero-carousel/rituals.png"
                     alt="Rituals Collection - zizo aura"
                     class="w-full h-full object-cover object-center select-none pointer-events-none"
                     loading="eager"
                     draggable="false" />
            </div>

            <!-- Slide 3: Victoria's Secret -->
            <div class="hero-slide w-full h-full shrink-0 relative" data-index="2" data-brand="victoria-secret" role="group" aria-roledescription="slide" aria-label="3 sur 4 : Victoria's Secret">
                <img src="/images/hero-carousel/victoria-secret.png"
                     alt="Victoria's Secret Collection - zizo aura"
                     class="w-full h-full object-cover object-center select-none pointer-events-none"
                     loading="lazy"
                     draggable="false" />
            </div>

            <!-- Slide 4: The Ordinary -->
            <div class="hero-slide w-full h-full shrink-0 relative" data-index="3" data-brand="ordinary" role="group" aria-roledescription="slide" aria-label="4 sur 4 : The Ordinary">
                <img src="/images/hero-carousel/ordinary.png"
                     alt="The Ordinary Clinical Skincare - zizo aura"
                     class="w-full h-full object-cover object-center select-none pointer-events-none"
                     loading="lazy"
                     draggable="false" />
            </div>

        </div>

        <!-- Left Manual Switch Arrow (Floating Glassmorphism Button) -->
        <button type="button"
                id="heroPrevBtn"
                aria-label="Image précédente"
                class="absolute left-3 sm:left-6 lg:left-8 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-13 sm:h-13 rounded-full bg-white/85 hover:bg-white text-zinc-900 shadow-[0_8px_30px_rgba(0,0,0,0.18)] backdrop-blur-md border border-white/60 flex items-center justify-center transition-all duration-300 hover:scale-108 active:scale-95 cursor-pointer">
            <i class="uil uil-angle-left-b text-xl sm:text-3xl -ml-0.5"></i>
        </button>

        <!-- Right Manual Switch Arrow (Floating Glassmorphism Button) -->
        <button type="button"
                id="heroNextBtn"
                aria-label="Image suivante"
                class="absolute right-3 sm:right-6 lg:right-8 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-13 sm:h-13 rounded-full bg-white/85 hover:bg-white text-zinc-900 shadow-[0_8px_30px_rgba(0,0,0,0.18)] backdrop-blur-md border border-white/60 flex items-center justify-center transition-all duration-300 hover:scale-108 active:scale-95 cursor-pointer">
            <i class="uil uil-angle-right-b text-xl sm:text-3xl -mr-0.5"></i>
        </button>

        <!-- Floating Bottom Brand Navigation Pills -->
        <div class="absolute bottom-3 sm:bottom-6 inset-x-0 z-20 flex items-center justify-center px-3 pointer-events-none">
            <nav id="heroBrandNav" class="pointer-events-auto inline-flex items-center gap-1 sm:gap-2 px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-full bg-black/45 hover:bg-black/60 backdrop-blur-xl border border-white/20 shadow-2xl transition-all duration-300" aria-label="Sélection de la marque">
                
                <!-- 1. Sol de Janeiro -->
                <button type="button"
                        class="hero-brand-pill is-active group"
                        data-slide-target="0"
                        role="tab"
                        aria-selected="true"
                        aria-label="Afficher Sol de Janeiro">
                    <span class="pill-dot"></span>
                    <span class="pill-label">Sol de Janeiro</span>
                </button>

                <!-- 2. Rituals -->
                <button type="button"
                        class="hero-brand-pill group"
                        data-slide-target="1"
                        role="tab"
                        aria-selected="false"
                        aria-label="Afficher Rituals">
                    <span class="pill-dot"></span>
                    <span class="pill-label">Rituals</span>
                </button>

                <!-- 3. Victoria's Secret -->
                <button type="button"
                        class="hero-brand-pill group"
                        data-slide-target="2"
                        role="tab"
                        aria-selected="false"
                        aria-label="Afficher Victoria's Secret">
                    <span class="pill-dot"></span>
                    <span class="pill-label">Victoria's Secret</span>
                </button>

                <!-- 4. The Ordinary -->
                <button type="button"
                        class="hero-brand-pill group"
                        data-slide-target="3"
                        role="tab"
                        aria-selected="false"
                        aria-label="Afficher The Ordinary">
                    <span class="pill-dot"></span>
                    <span class="pill-label">The Ordinary</span>
                </button>

                <!-- Divider & Play/Pause -->
                <div class="w-px h-4 bg-white/20 mx-1 hidden sm:block"></div>
                <button type="button"
                        id="heroAutoplayToggle"
                        class="p-1 sm:p-1.5 rounded-full text-white/80 hover:text-white transition-colors cursor-pointer hidden sm:flex items-center justify-center"
                        aria-label="Pause ou lecture du défilement"
                        title="Pause / Lecture">
                    <span class="hero-autoplay-icon-pause flex items-center justify-center">
                        <i class="uil uil-pause text-sm"></i>
                    </span>
                    <span class="hero-autoplay-icon-play hidden items-center justify-center text-pink-400">
                        <i class="uil uil-play text-sm"></i>
                    </span>
                </button>

            </nav>
        </div>

    </div>
</section>
