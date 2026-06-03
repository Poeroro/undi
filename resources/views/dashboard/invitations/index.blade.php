@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @include('dashboard.partials.nav')
    <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm text-[#706a63]">Kelola semua acara dari satu daftar</p>
            <h1 class="mt-2 text-3xl font-semibold">Undangan</h1>
        </div>
        <a href="{{ route('dashboard.invitations.create') }}" class="inline-flex justify-center rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white">Buat undangan</a>
    </div>

    <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($invitations as $invitation)
            <article class="rounded-2xl border border-[#eadfd4] bg-white/75 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="font-semibold">{{ $invitation->title }}</h2>
                        <p class="mt-1 text-sm text-[#706a63]">{{ $invitation->template?->name ?? 'Tanpa template' }}</p>
                    </div>
                    <span class="rounded-full bg-[#f4eee7] px-3 py-1 text-xs text-[#706a63]">{{ $invitation->status }}</span>
                </div>
                <dl class="mt-5 grid grid-cols-3 gap-3 text-sm">
                    <div class="rounded-xl bg-[#faf7f2] p-3"><dt class="text-[#706a63]">Views</dt><dd class="mt-1 font-semibold">{{ $invitation->view_count }}</dd></div>
                    <div class="rounded-xl bg-[#faf7f2] p-3"><dt class="text-[#706a63]">Share</dt><dd class="mt-1 font-semibold">{{ $invitation->share_count }}</dd></div>
                    <div class="rounded-xl bg-[#faf7f2] p-3"><dt class="text-[#706a63]">Tanggal</dt><dd class="mt-1 font-semibold">{{ $invitation->event_date?->format('d/m') }}</dd></div>
                </dl>
                <div class="mt-5 flex flex-wrap gap-2">
                    <a class="rounded-full border border-[#d9cbbd] px-4 py-2 text-sm" href="{{ $invitation->publicUrl() }}" target="_blank">Preview</a>
                    <button type="button" class="rounded-full border border-[#d9cbbd] px-4 py-2 text-sm" data-copy="{{ $invitation->publicUrl() }}">Copy link</button>
                    <a class="rounded-full bg-[#24211e] px-4 py-2 text-sm text-white" href="{{ route('dashboard.invitations.edit', $invitation) }}">Kelola</a>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-[#eadfd4] bg-white/75 p-8 text-sm text-[#706a63]">Belum ada undangan.</div>
        @endforelse
    </div>
    <div class="mt-8">{{ $invitations->links() }}</div>
</section>
@endsection
