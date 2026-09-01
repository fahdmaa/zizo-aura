<footer class="w-full bg-white py-12 sm:py-16 border-t border-zinc-100">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center text-center">
        
        <!-- Logo -->
        <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-tight text-black hover:opacity-80 transition-opacity lowercase mb-3">
            <span>zizo aura</span>
        </a>

        <p class="text-xs text-zinc-400 font-medium max-w-sm mb-6">
            Votre destination beauté &amp; rituels solaires : Victoria's Secret, Rituals, Sol de Janeiro.
        </p>

        <!-- Social Media Icons: Instagram & TikTok -->
        <div class="flex items-center justify-center gap-5 mb-8">
            <!-- Instagram -->
            <a href="https://www.instagram.com/zizo_aura_/"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="Instagram @zizo_aura_"
               class="btn-circle-action w-12 h-12 rounded-full bg-[#f8f9fa] hover:bg-[#ff1b7a] text-zinc-800 hover:text-white border-2 border-zinc-200 hover:border-[#ff1b7a] flex items-center justify-center text-xl shadow-xs">
                <i class="ti ti-brand-instagram"></i>
            </a>

            <!-- TikTok -->
            <a href="https://www.tiktok.com/@zizo_aura_"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="TikTok @zizo_aura_"
               class="btn-circle-action w-12 h-12 rounded-full bg-[#f8f9fa] hover:bg-[#ff1b7a] text-zinc-800 hover:text-white border-2 border-zinc-200 hover:border-[#ff1b7a] flex items-center justify-center text-xl shadow-xs">
                <i class="ti ti-brand-tiktok"></i>
            </a>
        </div>

        <!-- Copyright & Micro Links -->
        <div class="text-[11px] text-zinc-400 font-medium space-y-2">
            <div class="flex flex-wrap items-center justify-center gap-4 text-zinc-500">
                <a href="{{ route('shop.index') }}" class="hover:text-black transition-colors">Boutique</a>
                <span>&bull;</span>
                <a href="{{ route('contact') }}" class="hover:text-black transition-colors">Contact</a>
                <span>&bull;</span>
                <a href="{{ route('shop.index', ['category' => 'sol-de-janeiro']) }}" class="hover:text-black transition-colors">Sol de Janeiro</a>
                <span>&bull;</span>
                <a href="{{ route('shop.index', ['category' => 'victorias-secret']) }}" class="hover:text-black transition-colors">Victoria's Secret</a>
                <span>&bull;</span>
                <a href="{{ route('shop.index', ['category' => 'rituals']) }}" class="hover:text-black transition-colors">Rituals</a>
            </div>
            <p>&copy; {{ date('Y') }} zizo aura. Tous droits réservés.</p>
        </div>

    </div>
</footer>
