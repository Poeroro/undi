@extends('layouts.app')

@section('content')
<section class="relative overflow-hidden">
    <img class="absolute inset-0 h-full w-full object-cover opacity-20" src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=2200&q=80" alt="Dekorasi acara elegan" loading="eager">
    <div class="absolute inset-0 bg-gradient-to-b from-[#faf7f2]/90 via-[#faf7f2]/78 to-[#faf7f2]"></div>
    <div class="relative mx-auto grid min-h-[calc(100vh-80px)] max-w-7xl content-center gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[1.05fr_.95fr] lg:px-8">
        <div class="max-w-2xl">
            <p class="mb-4 text-sm font-medium uppercase tracking-[0.24em] text-[#a4785b]">Undangan digital SaaS</p>
            <h1 class="text-4xl font-semibold leading-tight text-[#24211e] sm:text-5xl lg:text-6xl">Undi</h1>
            <p class="mt-5 max-w-xl text-lg leading-8 text-[#655f58]">Kelola undangan, tamu, RSVP, ucapan, galeri, amplop digital, dan statistik dari satu dashboard yang bersih. Halaman publiknya dibuat mobile-first, karena tamu paling sering membukanya dari WhatsApp.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-[#24211e] px-6 py-3 text-sm font-medium text-white">Daftar gratis</a>
                <a href="{{ route('templates') }}" class="inline-flex items-center justify-center rounded-full border border-[#d8cabc] bg-white/70 px-6 py-3 text-sm font-medium text-[#3c3833]">Lihat template</a>
            </div>
        </div>
        <div class="relative mx-auto w-full max-w-[420px]">
            <div class="premium-surface rounded-[2rem] p-3">
                <div class="overflow-hidden rounded-[1.5rem] bg-[#201d19] text-white">
                    <img class="h-64 w-full object-cover" src="https://images.unsplash.com/photo-1529636798458-92182e662485?auto=format&fit=crop&w=1200&q=80" alt="Preview undangan" loading="eager">
                    <div class="p-7">
                        <p class="text-xs uppercase tracking-[0.25em] text-[#d7c3a8]">The Wedding of</p>
                        <h2 class="mt-3 text-4xl font-semibold">Andi & Sinta</h2>
                        <p class="mt-4 text-sm leading-6 text-[#ded6cf]">Sabtu, 08 Agustus 2026 · Jakarta Selatan</p>
                        <div class="mt-6 grid grid-cols-3 gap-2 text-center text-xs">
                            <div class="rounded-2xl bg-white/10 p-3"><span class="block text-xl font-semibold">42</span>Hari</div>
                            <div class="rounded-2xl bg-white/10 p-3"><span class="block text-xl font-semibold">18</span>Jam</div>
                            <div class="rounded-2xl bg-white/10 p-3"><span class="block text-xl font-semibold">09</span>Menit</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([['Undangan', 'Buat banyak undangan sesuai limit paket.'], ['Tamu', 'Link personal, kategori, WhatsApp, RSVP.'], ['Amplop', 'Bank, e-wallet, QRIS, dan tombol salin.'], ['Statistik', 'View, unique visitor, share, dan konversi.']] as [$featureTitle, $copy])
            <div class="rounded-2xl border border-[#eadfd4] bg-white/70 p-6">
                <h3 class="font-semibold text-[#24211e]">{{ $featureTitle }}</h3>
                <p class="mt-3 text-sm leading-6 text-[#706a63]">{{ $copy }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="bg-white/55 py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-[#a4785b]">Template</p>
                <h2 class="mt-3 text-3xl font-semibold">Pilihan awal yang rapi, bukan sekadar dekorasi.</h2>
            </div>
            <a href="{{ route('templates') }}" class="text-sm font-medium text-[#7a553f]">Lihat semua</a>
        </div>
        <div class="mt-8 grid gap-5 md:grid-cols-3">
            @forelse ($templates as $template)
                <article class="overflow-hidden rounded-2xl border border-[#eadfd4] bg-white">
                    <img class="h-52 w-full object-cover" src="{{ $template->preview_image_path }}" alt="{{ $template->name }}" loading="lazy">
                    <div class="p-5">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="font-semibold">{{ $template->name }}</h3>
                            @if ($template->is_premium)
                                <span class="rounded-full bg-[#f3e8dc] px-3 py-1 text-xs text-[#7a553f]">Premium</span>
                            @endif
                        </div>
                        <p class="mt-3 text-sm leading-6 text-[#706a63]">{{ $template->description }}</p>
                        <a class="mt-5 inline-flex rounded-full border border-[#d9cbbd] px-4 py-2 text-sm font-medium text-[#3c3833]" href="{{ route('dashboard.invitations.create', ['template_id' => $template->id]) }}">Pakai template</a>
                    </div>
                </article>
            @empty
                <p class="text-[#706a63]">Template akan tampil setelah seed dijalankan.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[.8fr_1.2fr] lg:px-8">
    <div>
        <p class="text-sm font-medium uppercase tracking-[0.2em] text-[#a4785b]">Cara kerja</p>
        <h2 class="mt-3 text-3xl font-semibold">Dari data acara ke link WhatsApp dalam alur yang tenang.</h2>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        @foreach ([['1', 'Isi detail', 'Pilih template, isi tanggal, lokasi, cerita, galeri, dan amplop digital.'], ['2', 'Kelola tamu', 'Import atau tambah manual, lalu buat link personal dan pesan WhatsApp.'], ['3', 'Pantau acara', 'Lihat RSVP, ucapan, statistik kunjungan, dan jumlah share.']] as [$num, $stepTitle, $copy])
            <div class="rounded-2xl border border-[#eadfd4] bg-white/70 p-6">
                <span class="grid size-10 place-items-center rounded-full bg-[#24211e] text-sm font-semibold text-white">{{ $num }}</span>
                <h3 class="mt-5 font-semibold">{{ $stepTitle }}</h3>
                <p class="mt-3 text-sm leading-6 text-[#706a63]">{{ $copy }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="bg-[#24211e] py-16 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-[#d7c3a8]">Paket</p>
                <h2 class="mt-3 text-3xl font-semibold">Mulai dari gratis, naik saat acara makin serius.</h2>
            </div>
            <a class="text-sm text-[#ead8bf]" href="{{ route('pricing') }}">Bandingkan paket</a>
        </div>
        <div class="mt-8 grid gap-5 md:grid-cols-4">
            @foreach ($plans as $plan)
                <article class="rounded-2xl border border-white/10 bg-white/[0.06] p-6">
                    <h3 class="font-semibold">{{ $plan->name }}</h3>
                    <p class="mt-4 text-3xl font-semibold">Rp{{ number_format($plan->price, 0, ',', '.') }}</p>
                    <p class="mt-3 text-sm leading-6 text-[#d9d2cb]">{{ $plan->description }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid gap-5 md:grid-cols-3">
        @foreach ([['Nadya, Bandung', 'Dashboard-nya enak dipakai. Daftar tamu dan RSVP jadi jauh lebih rapi.'], ['Raka, Jakarta', 'Template-nya terasa premium di layar HP, bukan sekadar halaman panjang yang ramai.'], ['Mira, Surabaya', 'Amplop digital dan ucapan bisa diatur tanpa minta bantuan developer.']] as [$name, $quote])
            <figure class="rounded-2xl border border-[#eadfd4] bg-white/70 p-6">
                <blockquote class="text-sm leading-7 text-[#4f4943]">“{{ $quote }}”</blockquote>
                <figcaption class="mt-5 text-sm font-medium">{{ $name }}</figcaption>
            </figure>
        @endforeach
    </div>
</section>

<section class="mx-auto max-w-4xl px-4 pb-20 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-semibold">Pertanyaan yang sering muncul</h2>
    <div class="mt-6 divide-y divide-[#e4d8cc] rounded-2xl border border-[#eadfd4] bg-white/70">
        @foreach ([['Apakah bisa pakai domain sendiri?', 'Bisa untuk paket Premium dan Exclusive. Struktur verifikasi domain sudah disiapkan.'], ['Apakah tamu bisa RSVP dari link WhatsApp?', 'Bisa. Link personal dapat membawa nama tamu dan batas jumlah pendamping.'], ['Apakah data tamu aman?', 'Data tamu hanya tampil di dashboard pemilik undangan dan panel admin yang berizin.']] as [$question, $answer])
            <div class="p-5">
                <h3 class="font-medium">{{ $question }}</h3>
                <p class="mt-2 text-sm leading-6 text-[#706a63]">{{ $answer }}</p>
            </div>
        @endforeach
    </div>
    <div class="mt-10 rounded-3xl bg-[#e7dacd] p-8 text-center">
        <h2 class="text-2xl font-semibold">Buat undangan pertama tanpa biaya.</h2>
        <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-[#625951]">Mulai dari template yang sudah rapi, lalu sesuaikan detail acara, tamu, dan pesan WhatsApp sesuai kebutuhan.</p>
        <a href="{{ route('register') }}" class="mt-6 inline-flex rounded-full bg-[#24211e] px-6 py-3 text-sm font-medium text-white">Daftar gratis</a>
    </div>
</section>
@endsection
