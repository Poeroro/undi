@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @include('dashboard.invitations.partials.section-nav', ['invitation' => $invitation])
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ([['Hadir', $summary['attending']], ['Tidak hadir', $summary['declined']], ['Masih ragu', $summary['maybe']]] as [$label, $value])
            <div class="rounded-2xl border border-[#eadfd4] bg-white/75 p-5">
                <p class="text-sm text-[#706a63]">{{ $label }}</p>
                <p class="mt-3 text-3xl font-semibold">{{ $value }}</p>
            </div>
        @endforeach
    </div>
    <div class="mt-6 rounded-3xl border border-[#eadfd4] bg-white/75">
        <div class="flex items-center justify-between gap-4 border-b border-[#eadfd4] px-5 py-4">
            <h1 class="font-semibold">RSVP</h1>
            <a class="rounded-full bg-[#24211e] px-4 py-2 text-sm text-white" href="{{ route('dashboard.invitations.rsvps.export', $invitation) }}">Export</a>
        </div>
        <div class="divide-y divide-[#efe5da]">
            @forelse ($rsvps as $rsvp)
                <div class="grid gap-3 px-5 py-4 md:grid-cols-[1fr_auto]">
                    <div>
                        <p class="font-medium">{{ $rsvp->name }}</p>
                        <p class="mt-1 text-sm text-[#706a63]">{{ $rsvp->attendance }} · {{ $rsvp->guests_count }} orang</p>
                        @if ($rsvp->notes)<p class="mt-2 text-sm text-[#514a43]">{{ $rsvp->notes }}</p>@endif
                    </div>
                    <time class="text-sm text-[#706a63]">{{ $rsvp->created_at?->diffForHumans() }}</time>
                </div>
            @empty
                <div class="px-5 py-10 text-sm text-[#706a63]">Belum ada RSVP.</div>
            @endforelse
        </div>
        <div class="px-5 py-4">{{ $rsvps->links() }}</div>
    </div>
</section>
@endsection
