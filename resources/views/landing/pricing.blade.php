@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <p class="text-sm font-medium uppercase tracking-[0.2em] text-[#a4785b]">Harga</p>
    <h1 class="mt-3 max-w-3xl text-4xl font-semibold">Pilih paket sesuai skala acara dan kebutuhan branding.</h1>
    <div class="mt-10 grid gap-5 md:grid-cols-4">
        @foreach ($plans as $plan)
            <article class="rounded-2xl border {{ $plan->is_featured ? 'border-[#a4785b] bg-[#fff8ef]' : 'border-[#eadfd4] bg-white/70' }} p-6">
                <h2 class="font-semibold">{{ $plan->name }}</h2>
                <p class="mt-4 text-3xl font-semibold">Rp{{ number_format($plan->price, 0, ',', '.') }}</p>
                <p class="mt-3 min-h-16 text-sm leading-6 text-[#706a63]">{{ $plan->description }}</p>
                <dl class="mt-6 space-y-3 text-sm text-[#514a43]">
                    <div class="flex justify-between gap-3"><dt>Undangan</dt><dd>{{ $plan->invitation_limit }}</dd></div>
                    <div class="flex justify-between gap-3"><dt>Tamu</dt><dd>{{ $plan->guest_limit }}</dd></div>
                    <div class="flex justify-between gap-3"><dt>Galeri</dt><dd>{{ $plan->gallery_limit }}</dd></div>
                    <div class="flex justify-between gap-3"><dt>Custom domain</dt><dd>{{ $plan->custom_domain ? 'Ya' : 'Tidak' }}</dd></div>
                </dl>
                <a href="{{ route('register') }}" class="mt-7 inline-flex w-full justify-center rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white">Mulai</a>
            </article>
        @endforeach
    </div>
</section>
@endsection
