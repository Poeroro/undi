@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @include('dashboard.invitations.partials.section-nav', ['invitation' => $invitation])
    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
        <form method="post" action="{{ route('dashboard.invitations.gift.store', $invitation) }}" class="rounded-3xl border border-[#eadfd4] bg-white/75 p-5">
            @csrf
            <h1 class="text-xl font-semibold">Tambah amplop</h1>
            <label class="mt-5 block text-sm font-medium">Tipe
                <select class="form-input mt-2" name="type"><option value="bank">Bank</option><option value="e_wallet">E-wallet</option><option value="qris">QRIS</option></select>
            </label>
            <label class="mt-4 block text-sm font-medium">Provider<input class="form-input mt-2" name="provider_name" placeholder="BCA, Mandiri, GoPay"></label>
            <label class="mt-4 block text-sm font-medium">Pemilik rekening<input class="form-input mt-2" name="account_holder"></label>
            <label class="mt-4 block text-sm font-medium">Nomor rekening<input class="form-input mt-2" name="account_number"></label>
            <label class="mt-4 block text-sm font-medium">Path QRIS<input class="form-input mt-2" name="qris_path"></label>
            <label class="mt-4 block text-sm font-medium">Instruksi<textarea class="form-input mt-2" name="instructions"></textarea></label>
            <label class="mt-4 flex items-center gap-2 text-sm"><input type="checkbox" name="is_visible" value="1" checked> Tampilkan</label>
            <button class="mt-5 w-full rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white">Simpan</button>
        </form>
        <div class="grid gap-4 md:grid-cols-2">
            @forelse ($gifts as $gift)
                <article class="rounded-2xl border border-[#eadfd4] bg-white/75 p-5">
                    <p class="text-sm uppercase tracking-[0.15em] text-[#a4785b]">{{ $gift->type }}</p>
                    <h2 class="mt-2 font-semibold">{{ $gift->provider_name ?: 'Amplop digital' }}</h2>
                    <p class="mt-2 text-sm text-[#706a63]">{{ $gift->account_holder }}</p>
                    <div class="mt-4 flex items-center justify-between gap-3 rounded-xl bg-[#faf7f2] p-3">
                        <code class="text-sm">{{ $gift->account_number ?: '-' }}</code>
                        @if ($gift->account_number)
                            <button class="rounded-full border border-[#d9cbbd] px-3 py-1 text-xs" data-copy="{{ $gift->account_number }}">Copy</button>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-[#eadfd4] bg-white/75 p-8 text-sm text-[#706a63]">Belum ada amplop digital.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
