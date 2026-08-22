@props(['services' => []])

<section class="border-y border-black/10 bg-[var(--surface-raised)] py-6">
    <div class="container-custom">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-black/10">
            @foreach($services as $service)
                <div class="flex items-center gap-4 py-4 sm:py-0 px-4 first:pl-0 last:pr-0">
                    <div class="w-11 h-11 rounded-full bg-[var(--terracotta-light)] flex items-center justify-center text-[var(--terracotta)] shrink-0">
                        <i data-lucide="{{ $service['icon'] }}" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900 mb-0.5">
                            {{ $service['title'] }}
                        </h4>
                        <p class="text-xs text-[var(--ink-subtle)] leading-snug">
                            {{ $service['desc'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
