@props(['highlights' => []])

<section class="py-20 bg-[var(--sable)]">
    <div class="container-custom">
        <div class="text-center mb-12">
            <h2 class="display-md mb-3 text-zinc-900">Collections</h2>
            <p class="text-[var(--ink-muted)] max-w-md mx-auto text-sm md:text-base">
                Explorez l'univers Sol de Janeiro par catégorie
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($highlights as $col)
                <a href="{{ $col['link'] }}" class="group relative block rounded-md overflow-hidden aspect-[4/5] shadow-sm hover:shadow-xl transition-all duration-500">
                    <!-- Image -->
                    <img src="{{ $col['image'] }}"
                         alt="{{ $col['title'] }}"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out" />

                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[oklch(0.15_0.02_30_/_0.85)] via-[oklch(0.15_0.02_30_/_0.3)] to-transparent"></div>

                    <!-- Content -->
                    <div class="absolute bottom-0 inset-x-0 p-7 flex flex-col items-start">
                        <span class="text-[11px] font-semibold tracking-widest uppercase text-[var(--or-muted)] mb-1">
                            {{ $col['subtitle'] }}
                        </span>
                        <h3 class="font-serif text-2xl text-white font-normal italic mb-2">
                            {{ $col['title'] }}
                        </h3>
                        <p class="text-xs md:text-sm text-white/80 line-clamp-2 leading-relaxed mb-4">
                            {{ $col['description'] }}
                        </p>
                        <span class="text-xs font-semibold tracking-widest uppercase text-[var(--or-muted)] inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            Découvrir &rarr;
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
