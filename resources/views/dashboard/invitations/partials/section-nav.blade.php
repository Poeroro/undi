<div class="mb-6 flex gap-2 overflow-x-auto pb-1 text-sm">
    @foreach ([
        ['Edit', route('dashboard.invitations.edit', $invitation)],
        ['Tamu', route('dashboard.invitations.guests', $invitation)],
        ['RSVP', route('dashboard.invitations.rsvps', $invitation)],
        ['Ucapan', route('dashboard.invitations.messages', $invitation)],
        ['Galeri', route('dashboard.invitations.gallery', $invitation)],
        ['Timeline', route('dashboard.invitations.stories', $invitation)],
        ['Amplop', route('dashboard.invitations.gift', $invitation)],
        ['Statistik', route('dashboard.invitations.statistics', $invitation)],
    ] as [$label, $url])
        <a class="whitespace-nowrap rounded-full border border-[#e3d6c8] bg-white/80 px-4 py-2 text-[#514a43]" href="{{ $url }}">{{ $label }}</a>
    @endforeach
</div>
