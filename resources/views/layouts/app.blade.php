<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? (($siteSettings['site_name'] ?? 'Undi').' - Undangan Digital Premium') }}</title>
    <meta name="description" content="{{ $description ?? ($siteSettings['meta_description'] ?? 'Platform undangan digital modern untuk acara personal dan profesional.') }}">
    <meta property="og:title" content="{{ $title ?? 'Undi' }}">
    <meta property="og:description" content="{{ $description ?? ($siteSettings['meta_description'] ?? 'Buat undangan digital yang rapi, personal, dan mudah dibagikan.') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased">
    <header class="sticky top-0 z-40 border-b border-[#eadfd4]/80 bg-[#faf7f2]/90 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid size-10 place-items-center rounded-xl bg-[#24211e] text-sm font-semibold text-white">U</span>
                <span class="text-base font-semibold tracking-wide">Undi</span>
            </a>
            <nav class="hidden items-center gap-7 text-sm text-[#706a63] md:flex">
                <a class="hover:text-[#24211e]" href="{{ route('templates') }}">Template</a>
                <a class="hover:text-[#24211e]" href="{{ route('pricing') }}">Harga</a>
                @auth
                    <a class="hover:text-[#24211e]" href="{{ route('dashboard') }}">Dashboard</a>
                @endauth
            </nav>
            <div class="flex items-center gap-2">
                @auth
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-full border border-[#d9cbbd] px-4 py-2 text-sm text-[#514a43]">Keluar</button>
                    </form>
                @else
                    <a class="hidden rounded-full px-4 py-2 text-sm text-[#514a43] sm:inline-flex" href="{{ route('login') }}">Masuk</a>
                    <a class="rounded-full bg-[#24211e] px-4 py-2 text-sm font-medium text-white" href="{{ route('register') }}">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @if (session('status'))
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-2xl border border-[#d8e6d3] bg-[#f4fbf0] px-4 py-3 text-sm text-[#3d6039]">{{ session('status') }}</div>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="border-t border-[#eadfd4] bg-white/50">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 text-sm text-[#706a63] sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>© {{ date('Y') }} Undi. Dibuat untuk undangan yang terasa personal.</p>
            <div class="flex gap-5">
                <a href="{{ route('pricing') }}">Harga</a>
                <a href="{{ route('templates') }}">Template</a>
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $siteSettings['support_whatsapp'] ?? config('undi.support_whatsapp')) }}">Support</a>
            </div>
        </div>
    </footer>
</body>
</html>
