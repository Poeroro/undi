@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @include('dashboard.invitations.partials.section-nav', ['invitation' => $invitation])
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ([['Total views', $stats['views']], ['Unique', $stats['unique_views']], ['Share WA', $stats['shares']], ['Konversi', $stats['conversion'].'%'], ['Terakhir', $stats['last_viewed_at']?->diffForHumans() ?: '-']] as [$label, $value])
            <div class="rounded-2xl border border-[#eadfd4] bg-white/75 p-5">
                <p class="text-sm text-[#706a63]">{{ $label }}</p>
                <p class="mt-3 text-2xl font-semibold">{{ $value }}</p>
            </div>
        @endforeach
    </div>
    <div class="mt-8 rounded-3xl border border-[#eadfd4] bg-white/75 p-6">
        <h1 class="font-semibold">Ringkasan undangan</h1>
        <p class="mt-3 text-sm leading-7 text-[#706a63]">Data statistik dasar dicatat dari setiap kunjungan halaman publik, termasuk hash IP, session, referrer, path, dan waktu kunjungan. Untuk skala besar, metrik ini siap dipindahkan ke job queue atau pipeline analytics terpisah.</p>
    </div>
</section>
@endsection
