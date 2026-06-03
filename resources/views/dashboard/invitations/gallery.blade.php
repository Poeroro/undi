@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @include('dashboard.invitations.partials.section-nav', ['invitation' => $invitation])
    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
        <form method="post" action="{{ route('dashboard.invitations.gallery.store', $invitation) }}" class="rounded-3xl border border-[#eadfd4] bg-white/75 p-5">
            @csrf
            <h1 class="text-xl font-semibold">Tambah galeri</h1>
            <label class="mt-5 block text-sm font-medium">Tipe
                <select class="form-input mt-2" name="type"><option value="image">Foto</option><option value="video">Video</option></select>
            </label>
            <label class="mt-4 block text-sm font-medium">URL foto / path storage<input class="form-input mt-2" name="image_path"></label>
            <label class="mt-4 block text-sm font-medium">URL video<input class="form-input mt-2" name="video_url"></label>
            <label class="mt-4 block text-sm font-medium">Caption<input class="form-input mt-2" name="caption"></label>
            <label class="mt-4 block text-sm font-medium">Urutan<input class="form-input mt-2" name="sort_order" type="number" min="0" value="0"></label>
            <button class="mt-5 w-full rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white">Tambah</button>
        </form>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($gallery as $item)
                <article class="overflow-hidden rounded-2xl border border-[#eadfd4] bg-white">
                    @if ($item->image_path)
                        <img class="h-48 w-full object-cover" src="{{ $item->image_path }}" alt="{{ $item->caption ?: 'Galeri' }}" loading="lazy">
                    @endif
                    <div class="p-4">
                        <p class="font-medium">{{ $item->caption ?: ucfirst($item->type) }}</p>
                        <p class="mt-1 text-sm text-[#706a63]">Urutan {{ $item->sort_order }} · {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-[#eadfd4] bg-white/75 p-8 text-sm text-[#706a63]">Belum ada galeri.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
