@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @include('dashboard.invitations.partials.section-nav', ['invitation' => $invitation])
    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
        <form method="post" action="{{ route('dashboard.invitations.guests.store', $invitation) }}" class="rounded-3xl border border-[#eadfd4] bg-white/75 p-5">
            @csrf
            <h1 class="text-xl font-semibold">Tambah tamu</h1>
            <label class="mt-5 block text-sm font-medium">Nama<input class="form-input mt-2" name="name" required></label>
            <label class="mt-4 block text-sm font-medium">WhatsApp<input class="form-input mt-2" name="whatsapp"></label>
            <label class="mt-4 block text-sm font-medium">Email<input class="form-input mt-2" name="email" type="email"></label>
            <label class="mt-4 block text-sm font-medium">Kategori
                <select class="form-input mt-2" name="category">
                    @foreach (config('undi.guest_categories') as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="mt-4 block text-sm font-medium">Maksimal tamu<input class="form-input mt-2" name="max_companions" type="number" min="1" max="20" value="1"></label>
            <label class="mt-4 block text-sm font-medium">Catatan<textarea class="form-input mt-2" name="notes"></textarea></label>
            <button class="mt-5 w-full rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white">Simpan tamu</button>
        </form>

        <div class="rounded-3xl border border-[#eadfd4] bg-white/75">
            <div class="grid gap-4 border-b border-[#eadfd4] px-5 py-4 md:grid-cols-[1fr_auto] md:items-center">
                <div>
                    <h1 class="font-semibold">Daftar tamu</h1>
                    <p class="mt-1 text-sm text-[#706a63]">Link personal memakai token unik dan query nama tamu.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <form method="post" action="{{ route('dashboard.invitations.guests.import', $invitation) }}" enctype="multipart/form-data" class="flex gap-2">
                        @csrf
                        <input class="max-w-44 rounded-full border border-[#d9cbbd] bg-white px-3 py-2 text-xs" name="file" type="file" accept=".xlsx,.xls,.csv" required>
                        <button class="rounded-full border border-[#d9cbbd] px-4 py-2 text-sm">Import</button>
                    </form>
                    <a class="rounded-full bg-[#24211e] px-4 py-2 text-sm text-white" href="{{ route('dashboard.invitations.guests.export', $invitation) }}">Export</a>
                </div>
            </div>
            <div class="divide-y divide-[#efe5da]">
                @forelse ($guests as $guest)
                    <div class="grid gap-3 px-5 py-4 md:grid-cols-[1fr_auto] md:items-center">
                        <div>
                            <p class="font-medium">{{ $guest->name }}</p>
                            <p class="mt-1 text-sm text-[#706a63]">{{ $guest->category }} · {{ $guest->whatsapp ?: 'tanpa WhatsApp' }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button class="rounded-full border border-[#d9cbbd] px-4 py-2 text-sm" data-copy="{{ $guest->invitation->publicUrl($guest) }}">Copy link</button>
                            @if ($guest->whatsapp)
                                <a class="rounded-full bg-[#24211e] px-4 py-2 text-sm text-white" href="https://wa.me/{{ preg_replace('/\D+/', '', $guest->whatsapp) }}?text={{ urlencode($guest->invitation->shareMessageFor($guest)) }}" target="_blank">WhatsApp</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-sm text-[#706a63]">Belum ada tamu.</div>
                @endforelse
            </div>
            <div class="px-5 py-4">{{ $guests->links() }}</div>
        </div>
    </div>
</section>
@endsection
