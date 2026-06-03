@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @include('dashboard.partials.nav')
    <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm text-[#706a63]">Selamat datang, {{ $user->name }}</p>
            <h1 class="mt-2 text-3xl font-semibold">Dashboard undangan</h1>
        </div>
        <a href="{{ route('dashboard.invitations.create') }}" class="inline-flex justify-center rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white">Buat undangan</a>
    </div>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ([['Undangan', $stats['invitations']], ['Tamu', $stats['guests']], ['RSVP hadir', $stats['attending']], ['Ucapan', $stats['messages']], ['Kunjungan', $stats['views']]] as [$label, $value])
            <div class="rounded-2xl border border-[#eadfd4] bg-white/75 p-5">
                <p class="text-sm text-[#706a63]">{{ $label }}</p>
                <p class="mt-3 text-3xl font-semibold">{{ number_format($value) }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 rounded-3xl border border-[#eadfd4] bg-white/75">
        <div class="flex items-center justify-between gap-4 border-b border-[#eadfd4] px-5 py-4">
            <h2 class="font-semibold">Undangan terbaru</h2>
            <span class="text-sm text-[#706a63]">Paket: {{ $user->subscription?->plan?->name ?? 'Belum aktif' }}</span>
        </div>
        <div class="divide-y divide-[#efe5da]">
            @forelse ($invitations as $invitation)
                <div class="grid gap-4 px-5 py-4 sm:grid-cols-[1fr_auto] sm:items-center">
                    <div>
                        <p class="font-medium">{{ $invitation->title }}</p>
                        <p class="mt-1 text-sm text-[#706a63]">{{ $invitation->event_date?->translatedFormat('d F Y') }} · {{ $invitation->guests_count }} tamu · {{ $invitation->view_count }} views</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a class="rounded-full border border-[#d9cbbd] px-4 py-2 text-sm" href="{{ $invitation->publicUrl() }}" target="_blank">Preview</a>
                        <a class="rounded-full bg-[#24211e] px-4 py-2 text-sm text-white" href="{{ route('dashboard.invitations.edit', $invitation) }}">Edit</a>
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-sm text-[#706a63]">Belum ada undangan.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
