@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @include('dashboard.invitations.partials.section-nav', ['invitation' => $invitation])
    <div class="rounded-3xl border border-[#eadfd4] bg-white/75">
        <div class="border-b border-[#eadfd4] px-5 py-4"><h1 class="font-semibold">Buku tamu / ucapan</h1></div>
        <div class="divide-y divide-[#efe5da]">
            @forelse ($messages as $message)
                <div class="grid gap-3 px-5 py-4 md:grid-cols-[1fr_auto]">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-medium">{{ $message->name }}</p>
                            <span class="rounded-full px-3 py-1 text-xs {{ $message->is_visible ? 'bg-[#eef7e8] text-[#496545]' : 'bg-[#f6eee7] text-[#80604b]' }}">{{ $message->is_visible ? 'Tampil' : 'Menunggu' }}</span>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-[#514a43]">{{ $message->message }}</p>
                    </div>
                    @unless ($message->is_visible)
                        <form method="post" action="{{ route('dashboard.invitations.messages.approve', [$invitation, $message->id]) }}">
                            @csrf
                            <button class="rounded-full bg-[#24211e] px-4 py-2 text-sm text-white">Setujui</button>
                        </form>
                    @endunless
                </div>
            @empty
                <div class="px-5 py-10 text-sm text-[#706a63]">Belum ada ucapan.</div>
            @endforelse
        </div>
        <div class="px-5 py-4">{{ $messages->links() }}</div>
    </div>
</section>
@endsection
