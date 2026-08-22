<section class="w-full bg-white relative overflow-hidden border-b border-zinc-100">
    <div class="w-full grid grid-cols-1 lg:grid-cols-12 min-h-[500px] sm:min-h-[580px] lg:min-h-[660px]">
        
        <!-- Left: Top-Left Aligned Text in French -->
        <div class="lg:col-span-6 flex flex-col items-start justify-start pt-8 sm:pt-12 lg:pt-16 pb-8 pl-4 sm:pl-8 lg:pl-16 xl:pl-24 pr-4 sm:pr-8 z-10">
            <h1 class="text-4xl sm:text-5xl lg:text-[58px] font-extrabold text-black tracking-tight leading-[1.06] mb-6">
                Offrez à votre peau<br>
                4 nouvelles saveurs !
            </h1>

            <p class="text-sm sm:text-base text-zinc-500 font-normal leading-relaxed max-w-md mb-8">
                Vous avez adoré nos élixirs éclat cultes, découvrez 4 nouvelles formules gorgées de soleil auxquelles vous ne pourrez pas résister.
            </p>

            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                <a href="{{ route('shop.index') }}" class="btn-hero-pill group">
                    <span>Découvrir nos produits</span>
                    <i class="ti ti-arrow-right ml-2 text-lg group-hover:translate-x-1 transition-transform"></i>
                </a>

                <a href="{{ route('contact') }}" class="btn-secondary-pill group">
                    <span>Un besoin spécifique ?</span>
                    <i class="ti ti-message-circle-question text-base group-hover:rotate-12 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Right: Aligned to the Max Right and Flush Bottom of the Viewport -->
        <div class="lg:col-span-6 flex items-end justify-center lg:justify-end w-full h-[420px] sm:h-[520px] lg:h-[660px] overflow-hidden select-none pointer-events-none pr-0 mr-0">
            <img src="/images/hero_cutout_with_aaahh.png"
                 alt="Offrez à votre peau 4 nouvelles saveurs - zizo aura"
                 class="h-full w-auto max-w-full lg:max-w-none object-contain object-bottom select-none pointer-events-auto hover:scale-[1.01] transition-transform duration-500 ease-out" />
        </div>

    </div>
</section>
