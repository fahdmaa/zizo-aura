<footer class="w-full bg-white py-12 sm:py-16 border-t border-zinc-100">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center text-center">
        
        <!-- Logo -->
        <a href="{{ route('home') }}" class="inline-flex items-center justify-center hover:opacity-85 transition-opacity mb-4 select-none" aria-label="zizo aura - Accueil">
            <img src="/images/logo.png" alt="zizo aura - Beauty & Care" class="h-10 sm:h-12 w-auto object-contain mx-auto" />
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
               class="btn-circle-action w-12 h-12 rounded-full bg-[#f8f9fa] hover:bg-[#ff1b7a] text-zinc-800 hover:text-white border-2 border-zinc-200 hover:border-[#ff1b7a] flex items-center justify-center text-2xl shadow-xs transition-all duration-300">
                <i class="uil uil-instagram"></i>
            </a>

            <!-- TikTok -->
            <a href="https://www.tiktok.com/@zizo_aura_"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="TikTok @zizo_aura_"
               class="btn-circle-action w-12 h-12 rounded-full bg-[#f8f9fa] hover:bg-[#ff1b7a] text-zinc-800 hover:text-white border-2 border-zinc-200 hover:border-[#ff1b7a] flex items-center justify-center text-xl shadow-xs transition-all duration-300">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.86 4.43c.04-.04.07-.08.1-.12.02-.03.04-.06.06-.09V9.17a8.28 8.28 0 0 0 5.57 2.14V7.87a4.87 4.87 0 0 1-.0-.18z"/></svg>
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
