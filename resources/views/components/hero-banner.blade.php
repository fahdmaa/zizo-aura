<section class="relative h-[100svh] min-h-[600px] overflow-hidden flex items-center justify-center">
    <!-- Background Image with Parallax -->
    <div id="hero-parallax"
         class="absolute -inset-[15%] bg-cover bg-center will-change-transform"
         style="background-image: url('https://images.unsplash.com/photo-1519125323398-675f0ddb6308?w=1800&q=85&fit=crop&crop=center');">
    </div>

    <!-- Gradient Overlay — Terracotta Wash -->
    <div class="absolute inset-0 bg-gradient-to-br from-[oklch(0.52_0.13_38_/_0.82)] via-[oklch(0.43_0.11_200_/_0.55)] to-[oklch(0.15_0.02_30_/_0.75)]"></div>

    <!-- Moroccan Geometric Overlay (Discreet) -->
    <div class="absolute inset-0 opacity-10 pointer-events-none"
         style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M40 0 L46 14 L60 8 L54 22 L68 28 L54 34 L60 48 L46 42 L40 56 L34 42 L20 48 L26 34 L12 28 L26 22 L20 8 L34 14 Z' fill='none' stroke='white' stroke-width='0.4'/%3E%3C/svg%3E&quot;); background-repeat: repeat;">
    </div>

    <!-- Hero Content -->
    <div class="container-custom relative z-10 flex flex-col items-start gap-6 max-w-4xl">
        <!-- Brand Label -->
        <span class="label text-[var(--or)] tracking-[0.2em] font-semibold">
            Sol de Janeiro
        </span>

        <!-- Headline -->
        <h1 class="display-xl text-white max-w-3xl leading-none">
            L'été brésilien,<br>
            <em class="text-[var(--or-muted)] font-normal not-italic font-serif">l'âme marocaine</em>
        </h1>

        <!-- Subheading -->
        <p class="text-base md:text-lg text-white/80 max-w-xl font-light leading-relaxed">
            Des soins corps nourrissants aux parfums Cheirosa enivrants.
            Découvrez la marque brésilienne qui célèbre chaque corps dans un écrin minimaliste.
        </p>

        <!-- CTA Action Buttons -->
        <div class="flex flex-wrap items-center gap-4 mt-2">
            <a href="#products" class="btn-primary">
                Découvrir la collection
            </a>
            <a href="#brand-story" class="btn-outline">
                Notre histoire
            </a>
        </div>
    </div>

    <!-- Scroll Down Indicator -->
    <a href="#products" aria-label="Défiler vers les produits" class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/70 animate-bounce z-10">
        <i data-lucide="chevron-down" class="w-7 h-7 stroke-1"></i>
    </a>
</section>
