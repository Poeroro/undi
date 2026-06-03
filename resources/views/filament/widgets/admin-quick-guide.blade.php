<x-filament-widgets::widget>
    <x-filament::section
        heading="Pusat operasi admin"
        description="Mulai dari template, lanjut ke undangan, lalu pantau tamu, RSVP, ucapan, transaksi, dan setting."
    >
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach ($actions as $action)
                <div class="flex flex-col gap-3 py-4 first:pt-0 last:pb-0 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-950 dark:text-white">{{ $action['title'] }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $action['description'] }}</p>
                    </div>
                    <div class="flex shrink-0 flex-wrap gap-2">
                        <a class="fi-btn fi-size-sm fi-btn-color-primary inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-semibold" href="{{ $action['url'] }}">
                            Buka
                        </a>
                        @if (! empty($action['secondaryUrl']))
                            <a class="fi-btn fi-size-sm fi-btn-color-gray inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-semibold" href="{{ $action['secondaryUrl'] }}">
                                {{ $action['secondaryLabel'] }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
