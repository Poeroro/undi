@php
    $cover = $invitation->cover_photo_path ?: $invitation->galleries->first()?->image_path ?: 'https://images.unsplash.com/photo-1529636798458-92182e662485?auto=format&fit=crop&w=1800&q=80';
    $profile = $invitation->profile_photo_path ?: $cover;
    $cover = $cover && ! str_starts_with($cover, 'http') ? asset('storage/'.$cover) : $cover;
    $profile = $profile && ! str_starts_with($profile, 'http') ? asset('storage/'.$profile) : $profile;
    $skinKey = $invitation->template?->slug ?: 'elegant-wedding';
    $skin = config("undi.template_skins.{$skinKey}", config('undi.template_skins.elegant-wedding'));
    $templateTheme = $invitation->template?->default_theme ?: ($skin['default_theme'] ?? []);
    $accent = $invitation->theme_color ?: ($templateTheme['color'] ?? '#a4785b');
    $fontKey = array_key_exists($invitation->theme_font, config('undi.theme_fonts')) ? $invitation->theme_font : ($templateTheme['font'] ?? 'Georgia');
    $fontFamily = data_get(config('undi.theme_fonts'), "{$fontKey}.family", data_get(config('undi.theme_fonts'), 'Georgia.family'));
    $guestLabel = $guestName ?: 'Bapak/Ibu/Saudara/i';
    $eventSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => $invitation->title,
        'startDate' => $eventDateTime?->toIso8601String(),
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'location' => [
            '@type' => 'Place',
            'name' => $invitation->venue_name,
            'address' => $invitation->venue_address,
        ],
        'image' => [$cover],
        'description' => $invitation->description,
    ];
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $invitation->title }}</title>
    <meta name="description" content="{{ $invitation->description ?: 'Undangan digital '.$invitation->title }}">
    <meta property="og:title" content="{{ $invitation->title }}">
    <meta property="og:description" content="{{ $invitation->description ?: 'Buka undangan digital kami.' }}">
    <meta property="og:image" content="{{ $cover }}">
    <meta property="og:type" content="website">
    <script type="application/ld+json">
        @json($eventSchema)
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#11100f] text-[#2a241f] antialiased" style="--accent: {{ $accent }}; --skin-main: {{ $skin['main_bg'] ?? '#fbf7f1' }}; --skin-soft: {{ $skin['soft_bg'] ?? '#faf7f2' }}; --skin-section: {{ $skin['section_bg'] ?? '#ffffff' }}; --skin-dark: {{ $skin['dark_bg'] ?? '#24211e' }}; font-family: {{ $fontFamily }}">
    <div id="invitation-cover" class="fixed inset-0 z-50 grid place-items-center bg-[#11100f] p-5 text-white">
        <img class="absolute inset-0 h-full w-full object-cover opacity-45" src="{{ $cover }}" alt="{{ $invitation->title }}" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/55 to-black/85"></div>
        <div class="invitation-cover__panel relative w-full max-w-sm text-center">
            <p class="text-xs uppercase tracking-[0.28em] text-[#ead8bf]">{{ $skin['cover_label'] ?? 'The Invitation' }}</p>
            <h1 class="mt-4 text-5xl font-semibold leading-tight">{{ $invitation->primary_name }}@if($invitation->secondary_name) <span class="block text-4xl">& {{ $invitation->secondary_name }}</span>@endif</h1>
            <div class="mt-8 rounded-3xl border border-white/15 bg-white/10 p-5 backdrop-blur-md">
                <p class="text-sm text-[#eee4d8]">Kepada Yth.</p>
                <p class="mt-2 text-xl font-medium">{{ $guestLabel }}</p>
            </div>
            <button class="mt-8 w-full rounded-full bg-white px-6 py-3 text-sm font-semibold text-[#1f1c19]" data-open-invitation data-opening-label="Membuka...">Buka undangan</button>
        </div>
        <div class="invitation-envelope-stage" aria-hidden="true">
            <div class="invitation-envelope">
                <div class="invitation-envelope__back"></div>
                <div class="invitation-envelope__card">
                    <span>{{ $invitation->primary_name }}</span>
                    @if($invitation->secondary_name)
                        <strong>& {{ $invitation->secondary_name }}</strong>
                    @endif
                </div>
                <div class="invitation-envelope__front"></div>
                <div class="invitation-envelope__flap"></div>
                <div class="invitation-envelope__seal"></div>
            </div>
        </div>
    </div>

    @if ($invitation->music_enabled && $invitation->music_path)
        <audio id="invitation-music" loop src="{{ str_starts_with($invitation->music_path, 'http') ? $invitation->music_path : asset('storage/'.$invitation->music_path) }}"></audio>
    @endif

    <main class="mx-auto max-w-md shadow-2xl" style="background: var(--skin-main)">
        <section class="relative min-h-screen overflow-hidden">
            <img class="absolute inset-0 h-full w-full object-cover" src="{{ $cover }}" alt="{{ $invitation->title }}" loading="eager">
            <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/35 to-black/80"></div>
            <div class="relative flex min-h-screen flex-col justify-end px-6 pb-12 text-white">
                <p class="text-xs uppercase tracking-[0.28em] text-[#ead8bf]">{{ config('undi.event_types')[$invitation->event_type] ?? 'Undangan' }}</p>
                <h1 class="mt-4 text-5xl font-semibold leading-tight">{{ $invitation->primary_name }}@if($invitation->secondary_name)<span class="block">& {{ $invitation->secondary_name }}</span>@endif</h1>
                <p class="mt-5 text-sm leading-7 text-[#f3ede5]">{{ $invitation->description }}</p>
                <div class="mt-8 grid grid-cols-4 gap-2 text-center text-xs" data-countdown="{{ $eventDateTime?->timestamp }}">
                    @foreach (['days' => 'Hari', 'hours' => 'Jam', 'minutes' => 'Menit', 'seconds' => 'Detik'] as $key => $label)
                        <div class="rounded-2xl border border-white/15 bg-white/10 p-3 backdrop-blur-md">
                            <span class="block text-2xl font-semibold" data-countdown-part="{{ $key }}">00</span>{{ $label }}
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="px-6 py-12 text-center">
            <img class="mx-auto size-28 rounded-full object-cover ring-8 ring-white" src="{{ $profile }}" alt="{{ $invitation->primary_name }}" loading="lazy">
            <p class="mt-8 text-sm uppercase tracking-[0.22em]" style="color: var(--accent)">{{ $skin['intro_label'] ?? 'Dengan penuh syukur' }}</p>
            <h2 class="mt-3 text-3xl font-semibold">{{ $invitation->title }}</h2>
            <p class="mt-5 text-sm leading-7 text-[#706a63]">{{ $invitation->host_name ?: 'Kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara kami.' }}</p>
        </section>

        <section class="border-y border-[#eadfd4] px-6 py-10" style="background: var(--skin-section)">
            <h2 class="text-2xl font-semibold">Detail acara</h2>
            <div class="mt-6 space-y-4 text-sm">
                <div class="rounded-2xl p-5" style="background: var(--skin-soft)">
                    <p class="text-[#706a63]">Tanggal & waktu</p>
                    <p class="mt-2 font-medium">{{ $invitation->event_date?->translatedFormat('l, d F Y') }} · {{ substr((string) $invitation->event_time, 0, 5) }} {{ $invitation->timezone }}</p>
                </div>
                <div class="rounded-2xl p-5" style="background: var(--skin-soft)">
                    <p class="text-[#706a63]">Lokasi</p>
                    <p class="mt-2 font-medium">{{ $invitation->venue_name }}</p>
                    <p class="mt-1 leading-6 text-[#706a63]">{{ $invitation->venue_address }}</p>
                    @if ($invitation->maps_url)
                        <a class="mt-4 inline-flex rounded-full px-4 py-2 text-sm text-white" href="{{ $invitation->maps_url }}" target="_blank" style="background: var(--skin-dark)">Buka lokasi</a>
                    @endif
                </div>
            </div>
        </section>

        @if ($invitation->galleries->isNotEmpty())
            <section class="px-6 py-12">
                <h2 class="text-2xl font-semibold">Galeri</h2>
                <div class="mt-6 grid grid-cols-2 gap-3">
                    @foreach ($invitation->galleries as $item)
                        @php $image = $item->image_path && ! str_starts_with($item->image_path, 'http') ? asset('storage/'.$item->image_path) : $item->image_path; @endphp
                        @if ($image)
                            <img class="aspect-[4/5] rounded-2xl object-cover" src="{{ $image }}" alt="{{ $item->caption ?: 'Galeri' }}" loading="lazy">
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        @if ($invitation->stories->isNotEmpty())
            <section class="px-6 py-12" style="background: var(--skin-section)">
                <h2 class="text-2xl font-semibold">Cerita kami</h2>
                <div class="mt-6 space-y-5">
                    @foreach ($invitation->stories as $story)
                        <article class="border-l-2 pl-5" style="border-color: var(--accent)">
                            <p class="text-sm text-[#706a63]">{{ $story->story_date?->translatedFormat('d F Y') }}</p>
                            <h3 class="mt-1 font-semibold">{{ $story->title }}</h3>
                            <p class="mt-2 text-sm leading-7 text-[#706a63]">{{ $story->description }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($invitation->youtube_url)
            <section class="px-6 py-12">
                <h2 class="text-2xl font-semibold">Video</h2>
                <div class="mt-6 aspect-video overflow-hidden rounded-3xl bg-black">
                    <iframe class="h-full w-full" src="{{ $invitation->youtube_url }}" title="Video undangan" loading="lazy" allowfullscreen></iframe>
                </div>
            </section>
        @endif

        @if ($invitation->maps_embed_url)
            <section class="px-6 py-12" style="background: var(--skin-section)">
                <h2 class="text-2xl font-semibold">Peta lokasi</h2>
                <div class="mt-6 aspect-video overflow-hidden rounded-3xl" style="background: var(--skin-soft)">
                    <iframe class="h-full w-full" src="{{ $invitation->maps_embed_url }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </section>
        @endif

        <section class="px-6 py-12">
            <h2 class="text-2xl font-semibold">Konfirmasi kehadiran</h2>
            @if (session('rsvp_status'))<p class="mt-4 rounded-2xl bg-[#eef7e8] p-4 text-sm text-[#496545]">{{ session('rsvp_status') }}</p>@endif
            <form method="post" action="{{ route('invitations.rsvp', $invitation->slug) }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="guest_token" value="{{ $guest?->personal_token }}">
                <input class="form-input" name="name" value="{{ old('name', $guestName) }}" placeholder="Nama" required>
                <select class="form-input" name="attendance" required>
                    <option value="attending">Hadir</option>
                    <option value="declined">Tidak hadir</option>
                    <option value="maybe">Masih ragu</option>
                </select>
                <input class="form-input" name="guests_count" type="number" min="1" max="{{ $guest?->max_companions ?: 20 }}" value="1" required>
                <textarea class="form-input min-h-28" name="notes" placeholder="Catatan opsional"></textarea>
                <button class="w-full rounded-full px-5 py-3 text-sm font-medium text-white" style="background: var(--skin-dark)">Kirim RSVP</button>
            </form>
        </section>

        <section class="px-6 py-12" style="background: var(--skin-section)">
            <h2 class="text-2xl font-semibold">Ucapan & doa</h2>
            @if (session('message_status'))<p class="mt-4 rounded-2xl bg-[#eef7e8] p-4 text-sm text-[#496545]">{{ session('message_status') }}</p>@endif
            <form method="post" action="{{ route('invitations.message', $invitation->slug) }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="guest_token" value="{{ $guest?->personal_token }}">
                <input class="form-input" name="name" value="{{ old('name', $guestName) }}" placeholder="Nama" required>
                <textarea class="form-input min-h-32" name="message" placeholder="Tulis ucapan" required></textarea>
                <button class="w-full rounded-full px-5 py-3 text-sm font-medium text-white" style="background: var(--skin-dark)">Kirim ucapan</button>
            </form>
            <div class="mt-8 space-y-3">
                @foreach ($invitation->messages as $message)
                    <div class="rounded-2xl p-4" style="background: var(--skin-soft)">
                        <p class="font-medium">{{ $message->name }}</p>
                        <p class="mt-2 text-sm leading-6 text-[#706a63]">{{ $message->message }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        @if ($invitation->giftAccounts->isNotEmpty())
            <section class="px-6 py-12">
                <h2 class="text-2xl font-semibold">Amplop digital</h2>
                <div class="mt-6 space-y-4">
                    @foreach ($invitation->giftAccounts as $gift)
                        <div class="rounded-3xl border border-[#eadfd4] bg-white p-5">
                            <p class="text-xs uppercase tracking-[0.2em]" style="color: var(--accent)">{{ $gift->type }}</p>
                            <h3 class="mt-2 font-semibold">{{ $gift->provider_name ?: 'Amplop digital' }}</h3>
                            <p class="mt-1 text-sm text-[#706a63]">{{ $gift->account_holder }}</p>
                            @if ($gift->account_number)
                                <div class="mt-4 flex items-center justify-between gap-3 rounded-2xl p-3" style="background: var(--skin-soft)">
                                    <code class="text-sm">{{ $gift->account_number }}</code>
                                    <button class="rounded-full border border-[#d9cbbd] px-3 py-1 text-xs" data-copy="{{ $gift->account_number }}">Copy</button>
                                </div>
                            @endif
                            @if ($gift->qris_path)
                                <img class="mt-4 rounded-2xl" src="{{ str_starts_with($gift->qris_path, 'http') ? $gift->qris_path : asset('storage/'.$gift->qris_path) }}" alt="QRIS" loading="lazy">
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="px-6 py-12 text-center text-white" style="background: var(--skin-dark)">
            <h2 class="text-2xl font-semibold">Bagikan undangan</h2>
            <img class="mx-auto mt-6 size-40 rounded-2xl bg-white p-2" src="{{ $invitation->qrPngUrl() }}" alt="QR Code undangan" loading="lazy">
            <div class="mt-6 flex flex-col gap-3">
                <a class="rounded-full bg-white px-5 py-3 text-sm font-semibold text-[#24211e]" href="{{ route('invitations.share', ['slug' => $invitation->slug, 'guest' => $guest?->personal_token, 'to' => $guestName]) }}" target="_blank">Share WhatsApp</a>
                <button class="rounded-full border border-white/20 px-5 py-3 text-sm" data-copy="{{ $invitation->publicUrl($guest) }}">Copy link undangan</button>
            </div>
        </section>
    </main>

    @if ($invitation->music_enabled && $invitation->music_path)
        <button class="fixed bottom-5 right-5 z-40 grid size-12 place-items-center rounded-full bg-white text-sm font-semibold shadow-lg" data-music-toggle aria-label="Toggle music">♪</button>
    @endif
</body>
</html>
