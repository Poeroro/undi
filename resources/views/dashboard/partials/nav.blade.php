<nav class="flex gap-2 overflow-x-auto pb-1 text-sm">
    @foreach ([
        ['Dashboard', route('dashboard')],
        ['Undangan', route('dashboard.invitations.index')],
    ] as [$label, $url])
        <a class="whitespace-nowrap rounded-full border border-[#e3d6c8] bg-white/70 px-4 py-2 text-[#514a43]" href="{{ $url }}">{{ $label }}</a>
    @endforeach
</nav>
