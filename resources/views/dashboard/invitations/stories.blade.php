@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @include('dashboard.invitations.partials.section-nav', ['invitation' => $invitation])
    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
        <form method="post" action="{{ route('dashboard.invitations.stories.store', $invitation) }}" class="rounded-3xl border border-[#eadfd4] bg-white/75 p-5">
            @csrf
            <h1 class="text-xl font-semibold">Tambah timeline</h1>
            <label class="mt-5 block text-sm font-medium">Judul<input class="form-input mt-2" name="title" required></label>
            <label class="mt-4 block text-sm font-medium">Tanggal<input class="form-input mt-2" name="story_date" type="date"></label>
            <label class="mt-4 block text-sm font-medium">Foto / path<input class="form-input mt-2" name="image_path"></label>
            <label class="mt-4 block text-sm font-medium">Deskripsi<textarea class="form-input mt-2 min-h-28" name="description" required></textarea></label>
            <button class="mt-5 w-full rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white">Tambah</button>
        </form>
        <div class="rounded-3xl border border-[#eadfd4] bg-white/75">
            <div class="divide-y divide-[#efe5da]">
                @forelse ($stories as $story)
                    <div class="px-5 py-4">
                        <p class="font-medium">{{ $story->title }}</p>
                        <p class="mt-1 text-sm text-[#706a63]">{{ $story->story_date?->translatedFormat('d F Y') }}</p>
                        <p class="mt-3 text-sm leading-6 text-[#514a43]">{{ $story->description }}</p>
                    </div>
                @empty
                    <div class="px-5 py-10 text-sm text-[#706a63]">Belum ada timeline.</div>
                @endforelse
            </div>
            <div class="px-5 py-4">{{ $stories->links() }}</div>
        </div>
    </div>
</section>
@endsection
